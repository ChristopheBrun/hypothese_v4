<?php

namespace app\modules\ephemerides\models\form;

use app\modules\ephemerides\lib\enums\ImageStatus;
use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\Tag;
use app\modules\hlib\helpers\hString;
use app\modules\hlib\HLib;
use app\modules\hlib\lib\enums\YesNo;
use app\modules\hlib\models\ModelSearchForm;
use app\modules\ephemerides\models\query\CalendarEntryQuery;
use Exception;
use yii\data\ActiveDataProvider;

/**
 * CalendarEntrySearchForm
 * Modèle pour le formulaire de recherche
 */
class CalendarEntrySearchForm extends ModelSearchForm
{
    /** @var boolean */
    public $enabled;
    /** @var  int @see enum ImageStatus */
    public $image;
    /** @var  int */
    public $article;
    /** @var  int */
    public $tag;
    /** @var  string */
    public $title;
    /** @var  string */
    public $body;
    /** @var  string */
    public $event_date;

    /** @var array au format ['day' => n° de jour, 'month' => n° de mois] */
    public $dateParams;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // fk
            [['tag'],
                'exist', 'targetClass' => Tag::class, 'targetAttribute' => 'id'],
            // filtres
            [['image', 'enabled', 'article'],
                'filter', 'filter' => function ($value) {
                // Ce sont des chaînes de caractères qui nous arrivent mais on veut des entiers pour garantir la validité des comparaisons strictes
                return $value !== "" ? (int)$value : $value;
            }],
            [['dateParams'],
                'each', 'rule' => [
                'filter', 'filter' => function ($value) {
                    $day = $value['day'];
                    $month = $value['month'];
                    return $day > 0 && $day < 32 && $month > 0 && $month < 13;
                }]
            ],
            // enums
            [['enabled'],
                'in', 'range' => YesNo::getKeys()],
            [['image'],
                'in', 'range' => ImageStatus::getKeys()],
            // string
            [['title', 'tag', 'body', 'event_date'],
                'filter', 'filter' => [hString::class, 'sanitize'],
                'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function search(array $params)
    {
        $query = CalendarEntry::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$params) {
            return $dataProvider;
        }

        if (!$this->load($params)) {
            throw new Exception(HLib::t('messages', 'loadError'));
        }

        if (!$this->validate($params)) {
            throw new Exception(HLib::t('messages', 'validationError'));
        }

        if ($this->event_date) {
            if (!preg_match('/(\d\d)-(\d\d)/', $this->event_date, $matches)) {
                throw new Exception(HLib::t('messages', 'validationError'));
            }

            $this->dateParams[] = ['day' => $matches[1], 'month' => $matches[2]];
        }

        $this->buildFilter($query);
        $query->joinWith('tags');

        return $dataProvider;
    }

    /**
     * @param CalendarEntryQuery $query
     */
    protected function buildFilter(CalendarEntryQuery $query)
    {
        // présence d'une image
        // NB : on n'utilise pas de switch ici car nous avons besoin d'une comparaison stricte sur la valeur de $this->image
        if ($this->image === ImageStatus::ST_WITH) {
            $query->andWhere(['not', ['image' => null]]);
        } elseif ($this->image === ImageStatus::ST_WITHOUT) {
            $query->andWhere(['image' => null]);
        }

        // Filtre par date
        if ($this->dateParams) {
            $sqlArray = [];
            foreach ($this->dateParams as $dateParam) {
                $sqlArray[] = sprintf(
                    'DAY(event_date) = %d AND MONTH(event_date) = %d',
                    intval($dateParam['day']), intval($dateParam['month'])
                );
            }

            $sqlStr = implode(' OR ', $sqlArray);
            $query->andWhere($sqlStr);
        }

        $query->andFilterWhere([
            'enabled' => $this->enabled,
            'tag.id' => $this->tag,
        ]);

        if (trim($this->title)) {
            $query->andWhere('MATCH (title) AGAINST (:title)', ['title' => "\"$this->title\""]);
        }

        if (trim($this->body)) {
            $query->andWhere('MATCH (body) AGAINST (:body)', ['body' => "\"$this->body\""]);
        }
    }

}

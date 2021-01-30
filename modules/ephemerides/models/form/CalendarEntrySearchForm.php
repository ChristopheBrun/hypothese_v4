<?php

namespace app\modules\ephemerides\models\form;

use app\modules\ephemerides\lib\enums\ImageStatus;
use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\Tag;
use app\modules\hlib\helpers\hString;
use app\modules\hlib\HLib;
use app\modules\hlib\lib\enums\YesNo;
use app\modules\ephemerides\models\query\CalendarEntryQuery;
use Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * CalendarEntrySearchForm
 * Modèle pour le formulaire de recherche
 */
class CalendarEntrySearchForm extends CalendarEntry
{
    /** @var string|null id de la catégorie demandée */
    public ?string $tag = null;

    /** @var array Liste des jours demandés */
    public array $dateParams = []; // au format [['day' => n° de jour, 'month' => n° de mois], ...]
    // @internal $dateParams doit être public pour pouvoir être traité dans validate()

    /**
     * @inheritdoc
     * @noinspection PhpUnusedParameterInspection
     */
    public function rules(): array
    {
        return [
            // fk
            [['tag'],
                'exist', 'targetClass' => Tag::class, 'targetAttribute' => 'id'],
            // filtres
            [['image', 'enabled'],
                'filter', 'filter' => function ($value) {
                // Ce sont des chaînes de caractères qui nous arrivent mais on veut des entiers pour garantir la validité des comparaisons strictes
                return $value !== "" ? (int)$value : $value;
            }],
            [['dateParams'],
                'each', 'rule' => [
                function ($attribute, $params, $validator, $current) {
                    $day = $current['day'];
                    $month = $current['month'];
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
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     * @throws Exception
     */
    public function search(array $params): ActiveDataProvider
    {
        $this->load($params);

        // Si une recherche par date est demandée, les infos sont dans le champ 'event_date' au format jj-mm
        if (ArrayHelper::getValue($params, 'event_date')) {
            if (!preg_match('/(\d\d)-(\d\d)/', $params['event_date'], $matches)) {
                throw new Exception(HLib::t('messages', 'validationError'));
            }

            $this->dateParams[] = ['day' => $matches[1], 'month' => $matches[2]];
        }

        $query = CalendarEntry::find()->with('tags');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $this->buildFilter($query);
        return $dataProvider;
    }

    /**
     * @param CalendarEntryQuery $query
     */
    protected function buildFilter(CalendarEntryQuery $query)
    {
//        // présence d'une image
//        // NB : on n'utilise pas de switch ici car nous avons besoin d'une comparaison stricte sur la valeur de $this->image
//        if ($this->image === ImageStatus::ST_WITH) {
//            $query->andWhere(['not', ['image' => null]]);
//        } elseif ($this->image === ImageStatus::ST_WITHOUT) {
//            $query->andWhere(['image' => null]);
//        }

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

        $query->andFilterWhere(['enabled' => $this->enabled]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'label', $this->body]);
        $query->andFilterWhere(['like', 'description', $this->description]);

        if ($this->tag) {
            $query->innerJoinWith('tags');
            $query->andWhere(['tag.id' => $this->tag]);
        }
    }

}

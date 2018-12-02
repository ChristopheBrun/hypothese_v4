<?php

namespace app\modules\ephemerides\models;

use app\modules\hlib\HLib;
use app\modules\hlib\models\ModelSearchForm;
use app\modules\ephemerides\models\query\CalendarEntryQuery;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * CalendarEntrySearchForm
 * Modèle pour le formulaire de recherche
 */
class CalendarEntrySearchForm extends ModelSearchForm
{
    /** @var array $dateOperators Liste des opérateurs pour les comparaisons sur les dates */
    public static $dateOperators;
    /** @var array $statusList Liste des filtres sur le statut */
    public static $statusList;
    /** @var array $imageStatusList Liste des filtres sur l'image */
    public static $imageStatusList;
    /** @var array $imageStatusList Liste des filtres sur les articles */
    public static $articleStatusList;

    const NO_OP = '';
    const SAME_DATE = 1;
    const SAME_DAY = 2;
    const SAME_MONTH = 3;
    const SAME_YEAR = 4;

    /** @var string */
    public $eventDateOperator;
    /** @var string */
    public $eventDateString;
    /** @var  int */
    public $status;
    /** @var  int */
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
    public $sessionKey;

    /**
     * Quelques initialisations...
     *
     * @param array $config Tableau de configuration
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        static::$dateOperators = [
            static::NO_OP => '',
            static::SAME_DATE => Yii::t('labels', 'Date (dd-mm-yyyy)'),
            static::SAME_DAY => Yii::t('labels', 'Calendar day (dd-mm)'),
            static::SAME_MONTH => Yii::t('labels', 'Month (mm)'),
            static::SAME_YEAR => Yii::t('labels', 'Year (yyyy)'),
        ];
        $this->eventDateOperator = static::NO_OP;

        static::$statusList = [
            static::ST_ALL => Yii::t('labels', 'n/a'),
            static::ST_ENABLED => Yii::t('labels', 'Enabled'),
            static::ST_DISABLED => Yii::t('labels', 'Disabled'),
        ];
        $this->status = static::ST_ALL;

        static::$imageStatusList = [
            static::ST_ALL => Yii::t('labels', 'n/a'),
            static::ST_WITH => Yii::t('labels', 'With image'),
            static::ST_WITHOUT => Yii::t('labels', 'Without image'),
        ];
        $this->image = static::ST_ALL;

        static::$articleStatusList = [
            static::ST_ALL => Yii::t('labels', 'n/a'),
            static::ST_WITH => Yii::t('labels', 'With article'),
            static::ST_WITHOUT => Yii::t('labels', 'Without article'),
        ];
        $this->article = static::ST_ALL;

        $this->sessionKey = CalendarEntry::class . '.filter';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'eventDateString' => HLib::t('labels', 'Date'),
            'status' => HLib::t('labels', 'Status'),
            'image' => HLib::t('labels', 'Image'),
            'article' => HLib::t('labels', 'Article'),
            'title' => HLib::t('labels', 'Title'),
            'body' => HLib::t('labels', 'Text'),
            'tag' => Yii::t('labels', 'Tag'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // filtres
            [['image', 'status', 'article'], 'filter', 'filter' => function ($value) {
                // Ce sont des chaînes de caractères qui nous arrivent mais on veut des entiers pour garantir la validité des comparaisons strictes
                return $value !== "" ? (int)$value : $value;
            }],
            // validateurs
            ['eventDateOperator', 'in', 'range' => array_keys(static::$dateOperators)],
            [['eventDateString', 'title', 'tag', 'body'], 'string'],
            ['status', 'in', 'range' => array_keys(static::$statusList)],
            ['image', 'in', 'range' => array_keys(static::$imageStatusList)],
            ['article', 'in', 'range' => array_keys(static::$articleStatusList)],
            ['tag', 'exist', 'targetClass' => Tag::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function hasActiveFilters()
    {
        return
            $this->eventDateOperator !== '' || $this->status !== '' || $this->image !== '' || $this->article !== ''
            || $this->title || $this->tag;
    }

    /**
     * @inheritdoc
     */
    public function displayActiveFilters($sep = ' - ')
    {
        $filters = [];

        if ($dateOp = ArrayHelper::getValue(static::$dateOperators, $this->eventDateOperator)) {
            $filters[] = $dateOp . ' = ' . $this->eventDateString;
        }

        if ($this->status !== '') {
            $filters[] = Yii::t('labels', static::$statusList[$this->status]);
        }

        if ($this->image !== '') {
            $filters[] = Yii::t('labels', static::$imageStatusList[$this->image]);
        }

        if ($this->article !== '') {
            $filters[] = Yii::t('labels', static::$articleStatusList[$this->article]);
        }

        if ($this->title) {
            $filters[] = Yii::t('labels', '(title)') . ' ' . $this->title;
        }

        if ($this->body) {
            $filters[] = Yii::t('labels', '(text)') . ' ' . $this->body;
        }

        if ($this->tag) {
            /** @var Tag $tag */
            $tag = Tag::findOne($this->tag);
            $filters[] = $tag ? $tag->label : '???';
        }

        return implode($sep, $filters);
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

        if (!$this->load($params) || !$this->validate()) {
            $this->error = static::ERR_KO;
            return $dataProvider;
        }

        // S'il y a des erreurs pendant les traitements, le statut interne de $this sera mis à jour par la méthode où a eu lieu l'erreur
        $this->error = static::ERR_OK;
        if ($this->eventDateOperator && $this->eventDateString) {
            $this->buildEventDateClause($query);
        }

        $this->buildImageClause($query);
        $this->buildArticleClause($query);
        $this->buildTagClause($query);

        $query->andFilterWhere(['enabled' => $this->status]);

        if (trim($this->title)) {
            $query->andWhere('MATCH (title) AGAINST (:title)', ['title' => "\"$this->title\""]);
        }

        if (trim($this->body)) {
            $query->andWhere('MATCH (body) AGAINST (:body)', ['body' => "\"$this->body\""]);
        }

        return $dataProvider;
    }

    /**
     * Analyse de la chaine $eventDateString, qui vient généralement d'une saisie utilisateur. On tente de reconnaitre un des formats attendus.
     * Chaque format est logiquement lié à un opérateur, que l'on ajoute au tableau renvoyé en guise de résultats.
     * Il est conseillé d'utiliser cet opérateur, calculé de façon cohérente avec la date demandée, plutôt qu'un opérateur directement issu d'une saisie
     * utilisateur.
     * todo_cbn Consolider l'UI en backend avec un masque en focntion de l'opérateur sélectionné
     *
     * @param $eventDateString
     * @return array|bool
     */
    private function parseEventDateString($eventDateString)
    {
        $dateFields = ['day' => null, 'month' => null, 'year' => null, 'op' => null];
        $matches = [];
        // nb : attention à l'ordre des tests : le premier motif reconnu sera exploité.
        // todo_cbn ajouter des délimiteurs ^ et $ pour éviter ça ?
        if (preg_match('#(\d\d)\D(\d\d)\D(\d\d\d\d)#', $eventDateString, $matches)) {
            // date au format : dd-mm-yyyy
            $dateFields['day'] = $matches[1];
            $dateFields['month'] = $matches[2];
            $dateFields['year'] = $matches[3];
            $dateFields['op'] = static::SAME_DATE;
        }
        elseif (preg_match('#(\d\d)\D(\d\d)#', $eventDateString, $matches)) {
            // date au format : dd-mm
            $dateFields['day'] = $matches[1];
            $dateFields['month'] = $matches[2];
            $dateFields['op'] = static::SAME_DAY;
        }
        elseif (preg_match('#(\d\d\d\d)#', $eventDateString, $matches)) {
            // date au format : yyyy
            $dateFields['year'] = $matches[1];
            $dateFields['op'] = static::SAME_YEAR;
        }
        elseif (preg_match('#(\d\d)#', $eventDateString, $matches)) {
            // date au format : mm
            $dateFields['month'] = $matches[1];
            $dateFields['op'] = static::SAME_MONTH;
        }
        else {
            // Y'a une couille dans le potage...
            return false;
        }

        return $dateFields;
    }

    /**
     * Ajout des clauses where correspondant au filtre sur les dates.
     * Cette méthode ne fait rien si aucun opérateur n'a été explicitement choisi ($this->eventDateOperator == NO_OP)
     * Elle ne vérifie pas la validité des dates saisies  ;si la date filtre est mal saisie, le résultat sera une liste vide.*
     * todo_cbn Reconnaitre d'autres séparateurs de date que '-' : '/' par exemple. Reconnaitre d'autres formats (yyyy-mm-dd)
     *
     * @param CalendarEntryQuery $query
     */
    private function buildEventDateClause(CalendarEntryQuery &$query)
    {
        // On lit la sate demandée. La méthode parseEventDateString() en extrait le jour/mois/année et déduit quel opérateur doit être
        // appliqué.
        if (($dateFields = $this->parseEventDateString($this->eventDateString))) {
            switch ($dateFields['op']) {
                case static::SAME_DATE:
                    $query->andWhere('event_date = :date', ['date' => implode('-', [$dateFields['year'], $dateFields['month'], $dateFields['day']])]);
                    break;
                case static::SAME_DAY:
                    $query->andWhere('DAY(event_date) = :day AND MONTH(event_date) = :month', ['day' => $dateFields['day'], 'month' => $dateFields['month']]);
                    break;
                case static::SAME_MONTH:
                    $query->andWhere('MONTH(event_date) = :month', ['month' => $dateFields['month']]);
                    break;
                case static::SAME_YEAR:
                    $query->andWhere('YEAR(event_date) = :year', ['year' => $dateFields['year']]);
                    break;
                default:
                    break;
            }

        }
        else {
            $this->error = static::ERR_KO;
        }
    }

    /**
     * Ajoute une clause de sélection s'il y a une condition sur la présence d'une image
     *
     * @param CalendarEntryQuery $query
     */
    private function buildImageClause(CalendarEntryQuery &$query)
    {
        // NB : on n'utilise pas de switch ici car nous avons besoin d'une comparaison stricte sur la valeur de $this->image
        if ($this->image === static::ST_WITH) {
            $query->andWhere(['not', ['image' => null]]);
        }
        elseif ($this->image === static::ST_WITHOUT) {
            $query->andWhere(['image' => null]);
        }
    }

    /**
     * Ajoute une clause de sélection s'il y a une condition sur la présence d'une article
     *
     * @param CalendarEntryQuery $query
     */
    private function buildArticleClause(CalendarEntryQuery &$query)
    {
        // NB : on n'utilise pas de switch ici car nous avons besoin d'une comparaison stricte sur la valeur de $this->article
        if ($this->article === static::ST_WITH) {
            $query->innerJoin('article_calendar_entry ace', 'id = ace.calendar_entry_id');
        }
        elseif ($this->article === static::ST_WITHOUT) {
            $query->leftJoin('article_calendar_entry ace', 'id = ace.calendar_entry_id')->andWhere('ace.article_id IS NULL');
        }
    }

    /**
     * @param CalendarEntryQuery $query
     */
    private function buildTagClause(CalendarEntryQuery &$query)
    {
        if ($this->tag) {
            $query->innerJoin('`calendar_entry_tag` jt', 'id = jt.calendar_entry_id AND jt.tag_id = :tag', ['tag' => $this->tag]);
        }
    }

    /**
     * @return string
     */
    public function getFilteringDate()
    {
        // On lit la sate demandée. La méthode parseEventDateString() en extrait le jour/mois/année et déduit quel opérateur doit être
        // appliqué.
        $out = '';
        if (($dateFields = $this->parseEventDateString($this->eventDateString))) {
            switch ($dateFields['op']) {
                case static::SAME_DATE:
                    $out = implode('-', [$dateFields['year'], $dateFields['month'], $dateFields['day']]);
                    break;
                case static::SAME_DAY:
                    $out = implode('-', [$dateFields['day'], $dateFields['month']]);
                    break;
                case static::SAME_MONTH:
                    $out = $dateFields['month'];
                    break;
                case static::SAME_YEAR:
                    $out = $dateFields['yeau'];
                    break;
                default:
                    break;
            }
        }
        else {
            $this->error = static::ERR_KO;
        }

        return $out;
    }

}

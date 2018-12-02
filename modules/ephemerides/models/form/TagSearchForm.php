<?php

namespace app\modules\ephemerides\models;

use app\modules\hlib\HLib;
use app\modules\hlib\models\ModelSearchForm;
use yii\data\ActiveDataProvider;

/**
 * TagSearchForm represents the model behind the search form about `app\models\Tag`.
 */
class TagSearchForm extends ModelSearchForm
{
    /** @var  string */
    public $label;

    /**
     * Quelques initialisations...
     *
     * @param array $config Tableau de configuration
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->sessionKey = Tag::class . '.filter';
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'label' => HLib::t('labels', 'Label'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // filtres
            ['label', 'string'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params)
    {
        $query = Tag::find();
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
        $query->andFilterWhere(['like', 'label', $this->label]);

        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function hasActiveFilters()
    {
        return $this->label !== '';
    }

    /**
     * @inheritdoc
     */
    public function displayActiveFilters($sep = ' - ')
    {
        $filters = [];

        if ($this->label) {
            $filters[] = $this->label;
        }

        return implode($sep, $filters);
    }

}

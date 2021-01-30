<?php

namespace app\modules\ephemerides\models\form;

use app\modules\ephemerides\models\query\TagQuery;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ephemerides\models\Tag;

/**
 * TagSearchForm represents the model behind the search form of `app\modules\ephemerides\models\Tag`.
 */
class TagSearchForm extends Tag
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['label', 'created_at', 'updated_at'],
                'safe'],
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
     */
    public function search(array $params): ActiveDataProvider
    {
        $this->load($params);

        $query = Tag::find()->with('calendarEntries');
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
     * @param TagQuery $query
     */
    protected function buildFilter(TagQuery $query)
    {
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'label', $this->label]);
    }
}

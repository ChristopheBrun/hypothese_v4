<?php

namespace app\modules\user\models\search;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\user\models\User;

/**
 * UserSearch represents the model behind the search form about `app\modules\user\models\User`.
 */
class UserSearch extends User
{
    /** @var  string[] A renseignersi l'utilisateur recherché doit avoir au moins un de ces rôles */
    public array $hasRoleIn;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function search(array $params): ActiveDataProvider
    {
        /** @var User $user */
        $user = Yii::createObject(User::class);
        $query = $user->find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // Filtre facultatiif sur le groupe
        if (isset($this->hasRoleIn)) {
            $query->innerJoinWith('authorizations')->andWhere(['item_name' => $this->hasRoleIn]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'logged_in_from' => $this->logged_in_from,
            'logged_in_at' => $this->logged_in_at,
            'confirmed_at' => $this->confirmed_at,
            'blocked_at' => $this->blocked_at,
            'registered_from' => $this->registered_from,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key]);

        return $dataProvider;
    }
}

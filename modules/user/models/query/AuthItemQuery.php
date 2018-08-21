<?php

namespace app\modules\user\models\query;

use app\modules\user\lib\enums\AuthItemType;
use app\modules\user\models\AuthItemChild;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\user\models\AuthItem]].
 *
 * @see \app\modules\user\models\AuthItem
 */
class AuthItemQuery extends ActiveQuery
{

    /**
     * @inheritdoc
     * @return \app\modules\user\models\AuthItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\user\models\AuthItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return $this
     */
    public function permissions()
    {
        return $this->andWhere(['type' => AuthItemType::PERMISSION]);
    }

    /**
     * @return $this
     */
    public function roles()
    {
        return $this->andWhere(['type' => AuthItemType::ROLE]);
    }

    /**
     * Sélectionne les permissions "racine" qui ont des rôles pour parent
     *
     * @return $this
     */
    public function rootPermissions()
    {
        return $this
            ->from('auth_item p')// on force le from() pour injecter un alias sur le premier appel à la table auth_item
            ->innerJoin('auth_item_child a', 'a.child = p.name')
            ->innerJoin('auth_item r', 'a.parent = r.name')
            ->andWhere(['p.type' => AuthItemType::PERMISSION, 'r.type' => AuthItemType::ROLE]);
    }

    /**
     * Sélectionne les rôles "racine"
     *
     * @return $this
     */
    public function rootRoles()
    {
        return $this
            ->andWhere(['NOT IN', 'name', AuthItemChild::find()->select('child')])
            ->andWhere(['type' => AuthItemType::ROLE]);
    }

    /**
     * Sélectionne les items font le parent est $itemName
     *
     * @param $itemName
     * @return $this
     */
    public function childrenFrom($itemName)
    {
        return $this
            ->innerJoin('auth_item_child a', 'a.child = name')
            ->andWhere(['a.parent' => $itemName]);
    }


}

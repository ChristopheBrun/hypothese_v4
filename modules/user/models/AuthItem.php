<?php

namespace app\modules\user\models;

use app\modules\ia\lib\TreeNode;
use app\modules\user\models\query\AuthItemQuery;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "auth_item".
 *
 * @property string           $name
 * @property integer          $type
 * @property string           $description
 * @property string           $rule_name
 * @property resource         $data
 * @property integer          $created_at
 * @property integer          $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property User[]           $users
 * @property AuthRule         $ruleName
 * @property AuthItemChild[]  $authItemChildren
 * @property AuthItemChild[]  $authItemChildren0
 * @property AuthItem[]       $children
 * @property AuthItem[]       $parents
 */
class AuthItem extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::class, 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('labels', 'Name'),
            'type' => Yii::t('labels', 'Type'),
            'description' => Yii::t('labels', 'Description'),
            'rule_name' => Yii::t('labels', 'Rule Name'),
            'data' => Yii::t('labels', 'Data'),
            'created_at' => Yii::t('labels', 'Created At'),
            'updated_at' => Yii::t('labels', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     * @return AuthItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuthItemQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::class, ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->viaTable('auth_assignment', ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::class, ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::class, ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::class, ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }

    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Renvoie un arbre contenant $this comme racine et, si $withChildren vaut true (valeur par défaut), la liste des noeuds descendants
     *
     * @param bool $withChildren
     * @return TreeNode
     */
    public function getTreeNode($withChildren = true)
    {
        $tree = new TreeNode(['id' => $this->name, 'data' => $this]);
        if (!$withChildren) {
            return $tree;
        }

        foreach (static::find()->childrenFrom($this->name)->all() as $child) {
            $tree->addChild($child->getTreeNode());
        }

        return $tree;
    }
}

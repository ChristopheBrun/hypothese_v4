<?php

namespace app\modules\cms\models;

use app\modules\cms\HCms;
use app\modules\cms\queries\WebTagsQuery;
use app\modules\hlib\HLib;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "web_tags".
 *
 * @property integer  $id
 * @property integer  $base_id
 * @property integer  $language_id
 * @property string   $label
 * @property string   $created_at
 * @property string   $updated_at
 *
 * @property BaseTag  $base
 * @property Language $language
 */
class WebTag extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%web_tags}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => function () {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['base_id', 'language_id', 'label'], 'required'],
            [['base_id', 'language_id'], 'integer'],
            ['base_id', 'exist', 'targetClass' => BaseTag::class, 'targetAttribute' => 'id'],
            ['language_id', 'exist', 'targetClass' => Language::class, 'targetAttribute' => 'id'],
            [['label'], 'string', 'max' => 128],
            [['base_id', 'language_id'], 'unique', 'targetAttribute' => ['base_id', 'language_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'base_id' => HCms::t('labels', 'Base tag'),
            'language_id' => HCms::t('labels', 'Language'),
            'label' => HLib::t('labels', 'Label'),
            'created_at' => HLib::t('labels', 'Created At'),
            'updated_at' => HLib::t('labels', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBase()
    {
        return $this->hasOne(BaseTag::class, ['id' => 'base_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::class, ['id' => 'language_id']);
    }

    /**
     * @inheritdoc
     * @return WebTagsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WebTagsQuery();
    }

    /**
     * Renvoie la liste des WebNews ayant $this comme tag
     *
     * @return array WebNews[]
     */
    public function getReferers()
    {
        return WebNews::find()->byTagId($this->id)->all();
    }
}

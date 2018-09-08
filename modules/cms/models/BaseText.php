<?php

namespace app\modules\cms\models;

use app\modules\cms\HCms;
use app\modules\cms\queries\BaseTextsQuery;
use app\modules\hlib\HLib;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "base_texts".
 *
 * @property string   $id
 * @property string   $code
 * @property string   $base_page_id
 * @property string   $created_at
 * @property string   $updated_at
 *
 * @property array    $webTexts [WebText]
 * @property BasePage $basePage
 */
class BaseText extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%base_texts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'base_page_id'], 'required'],
            ['code', 'string', 'max' => 20],
            ['base_page_id', 'exist', 'targetClass' => BasePage::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * @return array
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
    public function attributeLabels()
    {
        return [
            'code' => HLib::t('labels', 'Code'),
            'base_page_id' => HCms::t('labels', 'Base page'),
            'created_at' => HLib::t('labels', 'Created At'),
            'updated_at' => HLib::t('labels', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebTexts()
    {
        return $this->hasMany(WebText::class, ['base_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBasePage()
    {
        return $this->hasOne(BasePage::class, ['id' => 'base_page_id']);
    }

    /**
     * @inheritdoc
     * @return BaseTextsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BaseTextsQuery();
    }

}

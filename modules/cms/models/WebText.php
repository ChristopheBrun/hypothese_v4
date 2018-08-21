<?php

namespace app\modules\cms\models;

use app\modules\cms\HCms;
use app\modules\cms\queries\WebTextsQuery;
use app\modules\hlib\HLib;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "web_texts".
 *
 * @property string $id
 * @property string $base_id
 * @property string $language_id
 * @property string $title
 * @property string $subtitle
 * @property string $description
 * @property string $body
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Language $language
 * @property BaseText $base
 */
class WebText extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%web_texts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['base_id', 'language_id', 'title', 'body'], 'required'],
            ['language_id', 'exist', 'targetClass' => Language::class, 'targetAttribute' => 'id'],
            ['base_id', 'exist', 'targetClass' => BaseText::class, 'targetAttribute' => 'id'],
            ['body', 'string'],
            ['title', 'string', 'max' => 128],
            [['subtitle', 'description'], 'string', 'max' => 255],
            [['base_id', 'language_id'], 'unique', 'targetAttribute' => ['base_id', 'language_id']],
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
            'base_id' => HCms::t('labels', 'Base text'),
            'language_id' => HCms::t('labels', 'Language'),
            'title' => HLib::t('labels', 'Title'),
            'subtitle' => HLib::t('labels', 'Subtitle'),
            'description' => HLib::t('labels', 'Description'),
            'body' => HLib::t('labels', 'Body'),
            'created_at' => HLib::t('labels', 'Created At'),
            'updated_at' => HLib::t('labels', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBase()
    {
        return $this->hasOne(BaseText::className(), ['id' => 'base_id']);
    }

    /**
     * @inheritdoc
     * @return WebTextsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WebTextsQuery();
    }
}

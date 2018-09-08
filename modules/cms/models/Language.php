<?php

namespace app\modules\cms\models;

use app\modules\cms\HCms;
use app\modules\cms\queries\LanguagesQuery;
use app\modules\hlib\HLib;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "languages".
 *
 * @property string $id
 * @property string $name
 * @property string $iso_639_code
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Language[] $languages
 */
class Language extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%languages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'iso_639_code'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['iso_639_code'], 'string', 'max' => 2],
            [['iso_639_code'], 'unique'],
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
            'id' => HLib::t('labels', 'ID'),
            'name' => HLib::t('labels', 'Name'),
            'iso_639_code' => HCms::t('labels', 'Code'),
            'created_at' => HLib::t('labels', 'Created At'),
            'updated_at' => HLib::t('labels', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasMany(Language::class, ['language_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return LanguagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LanguagesQuery(get_called_class());
    }
}

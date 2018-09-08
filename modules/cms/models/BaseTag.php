<?php

namespace app\modules\cms\models;

use app\modules\cms\queries\BaseTagsQuery;
use app\modules\hlib\HLib;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "base_tags".
 *
 * @property integer $id
 * @property string $code
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BaseNewsBaseTag[] $baseNewsBaseTags
 * @property BaseNews[] $baseNews
 * @property WebTag[] $webTags
 */
class BaseTag extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%base_tags}}';
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
            ['code', 'required'],
            ['code', 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'created_at' => HLib::t('labels', 'Created At'),
            'updated_at' => HLib::t('labels', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseNewsBaseTags()
    {
        return $this->hasMany(BaseNewsBaseTag::class, ['base_tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseNews()
    {
        return $this->hasMany(BaseNews::class, ['id' => 'base_news_id'])->viaTable('base_news_base_tag', ['base_tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebTags()
    {
        return $this->hasMany(WebTag::class, ['base_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return BaseTagsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BaseTagsQuery();
    }
}

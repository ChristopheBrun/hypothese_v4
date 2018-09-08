<?php

namespace app\modules\cms\models;

use app\modules\cms\queries\BaseNewsBaseTagQuery;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "base_news_base_tag".
 *
 * @property integer $base_news_id
 * @property integer $base_tag_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BaseNews $baseNews
 * @property BaseTag $baseTag
 */
class BaseNewsBaseTag extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%base_news_base_tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['base_news_id', 'base_tag_id', 'created_at', 'updated_at'], 'required'],
            [['base_news_id', 'base_tag_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'base_news_id' => Yii::t('labels', 'Base News ID'),
            'base_tag_id' => Yii::t('labels', 'Base Tag ID'),
            'created_at' => Yii::t('labels', 'Created At'),
            'updated_at' => Yii::t('labels', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseNews()
    {
        return $this->hasOne(BaseNews::class, ['id' => 'base_news_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseTag()
    {
        return $this->hasOne(BaseTag::class, ['id' => 'base_tag_id']);
    }

    /**
     * @inheritdoc
     * @return BaseNewsBaseTagQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BaseNewsBaseTagQuery(get_called_class());
    }
}

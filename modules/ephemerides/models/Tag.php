<?php

namespace app\modules\ephemerides\models;

use app\modules\hlib\behaviors\SitemapableBehavior;
use app\modules\hlib\HLib;

use app\modules\ephemerides\models\query\TagQuery;
use SimpleXMLElement;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;

/**
 * This is the model class for table "tags".
 *
 * @property string $id
 * @property string $label
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CalendarEntryTag[] $calendarEntryTags
 * @property CalendarEntry[] $calendarEntries
 */
class Tag extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label'],
                'required'],
            // string
            [['label'],
                'filter', 'filter' => 'strip_tags'],
            [['label'],
                'filter', 'filter' => 'trim'],
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
            [
                'class' => SitemapableBehavior::class,
                'filename' => 'sitemap.tags.xml',
                'callback' => function (SimpleXMLElement $xml) {
                    /** @var Tag $tag */
                    foreach (Tag::find()->all() as $tag) {
                        $url = $xml->addChild('url');
                        $url->addChild('loc', Html::encode(Url::to(['/calendar-entries/post-search', 'tag' => $tag->id], true)));
                        $url->addChild('changefreq', 'monthly');
                        $url->addChild('lastmod', $tag->updated_at);
                    }

                    return $xml;
                }
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
            'label' => HLib::t('labels', 'Label'),
            'created_at' => HLib::t('labels', 'Created At'),
            'updated_at' => HLib::t('labels', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     * @return TagQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TagQuery();
    }

    /**
     * @return ActiveQuery
     */
    public function getCalendarEntryTags()
    {
        return $this->hasMany(CalendarEntryTag::class, ['tag_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getCalendarEntries()
    {
        return $this->hasMany(CalendarEntry::class, ['id' => 'calendar_entry_id'])->viaTable('calendar_entry_tag', ['tag_id' => 'id']);
    }

    /**
     * Calcule un 'slug' pour ce modèle
     * todo_cbn : voir pourquoi le SluggableBehavior ne fonctionne pas ici (cd issues #33)
     *
     * @return string
     */
    public function getSlug()
    {
        return Inflector::slug($this->label);
    }

}

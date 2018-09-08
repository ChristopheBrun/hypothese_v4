<?php

namespace app\modules\cms\models;

use app\modules\cms\HCms;
use app\modules\cms\queries\WebNewsQuery;
use app\modules\hlib\helpers\hString;
use app\modules\hlib\HLib;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "web_news".
 *
 * @property integer  $id
 * @property integer  $base_id
 * @property integer  $language_id
 * @property string   $body
 * @property string   $title
 * @property string   $description
 * @property string   $created_at
 * @property string   $updated_at
 *
 * @property BaseNews $base
 * @property Language $language
 */
class WebNews extends ActiveRecord
{
    const DATE_FORMAT_DAY = 'd-m-Y';

    /** @var array $tags [WebTag] */
    private $tags = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%web_news}}';
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
            [['base_id', 'language_id', 'body', 'title'], 'required'],
            [['base_id', 'language_id'], 'integer'],
            ['base_id', 'exist', 'targetClass' => BaseNews::class, 'targetAttribute' => 'id'],
            ['language_id', 'exist', 'targetClass' => Language::class, 'targetAttribute' => 'id'],
            [['body'], 'string'],
            [['title'], 'string', 'max' => 128],
            [['description'], 'string', 'max' => 255],
            [['base_id', 'language_id'], 'unique', 'targetAttribute' => ['base_id', 'language_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'base_id' => HCms::t('labels', 'Base news'),
            'language_id' => HCms::t('labels', 'Language'),
            'body' => HLib::t('labels', 'Body'),
            'title' => HLib::t('labels', 'Title'),
            'description' => HLib::t('labels', 'Description'),
            'created_at' => HLib::t('labels', 'Created At'),
            'updated_at' => HLib::t('labels', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBase()
    {
        return $this->hasOne(BaseNews::class, ['id' => 'base_id']);
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
     * @return WebNewsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WebNewsQuery();
    }

    /**
     * Renvoie la liste des tags associés à $this
     *
     * @param bool $useCache utilise un cache interne. false => on relance la requête à chaque fois. Vaut true par défaut.
     * @return array [WebTag]
     */
    public function getTags($useCache = true)
    {
        if (!$useCache || is_null($this->tags)) {
            $this->tags = WebTag::find()->forWebNews($this->id)->addOrderBy('web_tags.label ASC')->all();
        }

        return $this->tags;
    }

    /**
     * Renvoie la liste des noms des tags associés à $this
     *
     * @return array
     */
    public function getTagsNames()
    {
        return ArrayHelper::getColumn($this->getTags(), 'label');
    }

    /**
     * Calcule un 'slug' pour ce modèle
     * todo_cbn : voir pourquoi le SluggableBehavior ne fonctionne pas ici (cd issues #33)
     *
     * @return string
     */
    public function getSlug()
    {
        return hString::slugify($this->title);
    }

    /**
     * Renvoie l'actualité traduite immédiatement antérieure à $this
     *
     * @param boolean $onlyActive true => seules les news actives sont exploitées
     * @return WebNews
     */
    public function getPrevious($onlyActive = true)
    {
        $previous = $this->base->getPrevious($onlyActive);
        if ($previous) {
            return WebNews::find()->byBaseNewsId($previous->id)->byLanguageId($this->language_id)->one();
        }

        return null;
    }

    /**
     * Renvoie l'actualité immédiatement postérieure à $this
     *
     * @param boolean $onlyActive true => seules les news actives sont renvoyées
     * @return WebNews
     */
    public function getNext($onlyActive = true)
    {
        $next = $this->base->getNext($onlyActive);
        if ($next) {
            return WebNews::find()->byBaseNewsId($next->id)->byLanguageId($this->language_id)->one();
        }

        return null;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->base->enabled;
    }

}

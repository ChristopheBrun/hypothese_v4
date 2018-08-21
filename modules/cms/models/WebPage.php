<?php

namespace app\modules\cms\models;

use app\modules\cms\HCms;
use app\modules\hlib\HLib;
use app\modules\cms\queries\WebPagesQuery;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "web_pages".
 *
 * @property string   $id
 * @property string   $base_id
 * @property string   $language_id
 * @property string   $title
 * @property string   $menu_title
 * @property string   $meta_description
 * @property string   $meta_keywords
 * @property integer  $enabled
 * @property string   $created_at
 * @property string   $updated_at
 *
 * @property BasePage $base
 * @property Language $language
 */
class WebPage extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%web_pages}}';
    }

    /**
     * Initialisation avec les valeurs par défaut
     */
    public function init()
    {
        parent::init();
        $this->language_id = 1; // français
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['base_id', 'language_id', 'title'], 'required'],
            ['language_id', 'exist', 'targetClass' => Language::class, 'targetAttribute' => 'id'],
            ['base_id', 'exist', 'targetClass' => BasePage::class, 'targetAttribute' => 'id'],
            ['title', 'string', 'max' => 128],
            ['title', 'number'],
            ['title', 'match', 'pattern' => '/^\d+$/'],
            ['menu_title', 'string', 'max' => 40],
            ['menu_title', 'default', 'value' => null],
            [['meta_description', 'meta_keywords'], 'string', 'max' => 255],
            [['base_id', 'language_id'], 'unique', 'targetAttribute' => ['base_id', 'language_id']],
        ];
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
    public function attributeLabels()
    {
        return [
            'id' => HLib::t('labels', 'ID'),
            'title' => HLib::t('labels', 'Title'),
            'menu_title' => HCms::t('labels', 'Menu title'),
            'meta_description' => HCms::t('labels', 'Meta Description'),
            'meta_keywords' => HCms::t('labels', 'Meta Keywords'),
            'base_id' => HCms::t('labels', 'Base page'),
            'language_id' => HCms::t('labels', 'Language'),
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
        return $this->hasOne(BasePage::className(), ['id' => 'base_id']);
    }

    /**
     * @inheritdoc
     * @return WebPagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WebPagesQuery();
    }

    /**
     * Renvoie le texte de code $code avec la même langue que $this
     *
     * @param string  $code
     * @return WebText|array|null
     */
    public function getText($code)
    {
        return WebText::find()->byWebPageAndCode($this, $code)->one();
    }

    /**
     * renvoie la liste des textes associés à cette page (même page/racine, même langue)
     *
     * @return WebText[]|array
     */
    public function getTexts()
    {
        return WebText::find()->byWebPage($this)->all();
    }

}

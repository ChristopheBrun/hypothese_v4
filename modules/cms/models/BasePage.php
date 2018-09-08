<?php

namespace app\modules\cms\models;

use app\modules\hlib\behaviors\SitemapableBehavior;
use app\modules\cms\HCms;
use app\modules\cms\queries\BasePagesQuery;
use app\modules\hlib\HLib;
use SimpleXMLElement;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;


/**
 * This is the model class for table "base_pages".
 *
 * @property string  $id
 * @property string  $code
 * @property string  $parent_page_id
 * @property string  $route
 * @property string  $redirect_to
 * @property integer $menu_index
 * @property integer $enabled
 * @property string  $created_at
 * @property string  $updated_at
 *
 * @property array   $webPages WebPage[]
 * @property array   $baseTexts BaseText[]
 */
class BasePage extends ActiveRecord
{
    /** @var  array */
//    private $updatedBaseTextsIds;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%base_pages}}';
    }

    /**
     * Initialisation avec les valeurs par défaut
     */
    public function init()
    {
        parent::init();

        // valeurs par défaut
        $this->enabled = true;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['code', 'required'],
            ['code', 'unique'],
            ['code', 'string', 'max' => 50],
            ['parent_page_id', 'exist', 'targetAttribute' => 'id'],
            ['menu_index', 'integer', 'min' => 0],
            ['menu_index', 'default', 'value' => null],
            ['enabled', 'boolean'], // not null default 1
            ['route', 'string', 'max' => 128],
            ['route', 'default', 'value' => null],
            ['redirect_to', 'string', 'max' => 128],
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
            [
                'class' => SitemapableBehavior::class,
                'filename' => 'sitemap.pages.xml',
                'callback' => function (SimpleXMLElement $xml) {
                    foreach (BasePage::find()->enabled()->withRoute()->all() as $page) {
                        $url = $xml->addChild('url');
                        $url->addChild('loc', Html::encode(Url::to([$page->route], true)));
                        // todo_cbn Abstraire le code de la page d'accueil (fichier de config du module ?)
                        if ($page->code == 'accueil') {
                            // la page d'accueil change tous les jours ...
                            $url->addChild('changefreq', 'daily');
                            $url->addChild('priority', '0.6');
                        }
                        else {
                            // ... les autres pages ne changent pratiquement jamais
                            $url->addChild('changefreq', 'yearly');
                            $url->addChild('priority', '0.4');
                        }

                        $url->addChild('lastmod', $page->updated_at);
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
            'code' => HLib::t('labels', 'Code'),
            'parent_page_id' => HCms::t('labels', 'Parent page'),
            'route' => HCms::t('labels', 'Route'),
            'redirect_to' => HCms::t('labels', 'Redirect to'),
            'enabled' => HLib::t('labels', 'Enabled'),
            'menu_index' => HCms::t('labels', 'Menu index'),
            'created_at' => HLib::t('labels', 'Created At'),
            'updated_at' => HLib::t('labels', 'Updated At'),
            'baseTexts' => HCms::t('labels', 'Base texts'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(BasePage::class, ['id' => 'parent_page_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(BasePage::class, ['parent_page_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebPages()
    {
        return $this->hasMany(WebPage::class, ['base_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseTexts()
    {
        return $this->hasMany(BaseText::class, ['base_page_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return BasePagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BasePagesQuery();
    }

    /**
     * Renvoie la liste des identifiants des textes/racines associés à $this
     *
     * @return array
     */
    public function baseTextsIds()
    {
        return ArrayHelper::getColumn($this->baseTexts, 'id');
    }

//    /**
//     * Assignation en masse des attributs avec prise en compte de la liste des ids des textes
//     * todo_cbn cf. issue #20
//     *
//     * @inheritdoc
//     */
//    public function load($data, $formName = null)
//    {
//        $this->updatedBaseTextsIds = ArrayHelper::getValue($data, 'BasePage.baseTexts', []);
//        if ($this->updatedBaseTextsIds === "") {
//            $this->updatedBaseTextsIds = [];
//        }
//
//        return parent::load($data, $formName);
//    }

//    /**
//     * Mise à jour de l'objet.
//     * Si $saveRelated vaut 'true', la table d'association avec les tags est mise à jour en même temps.
//     *
//     * @param bool|true  $runValidation
//     * @param null       $attributeNames
//     * @param bool|false $saveRelated
//     * @return bool
//     */
//    public function save($runValidation = true, $attributeNames = null, $saveRelated = false)
//    {
//        $transaction = Yii::$app->db->beginTransaction();
//        try {
//            if (!parent::save($runValidation, $attributeNames)) {
//                throw new Exception('Erreur sur parent::save()');
//            }
//
//            if ($saveRelated && !BasePageBaseTextQuery::updateBaseTextsForPage($this->baseTextsIds(), $this->updatedBaseTextsIds, $this->id)) {
//                throw new Exception("Erreur sur BasePageBaseTextQuery::updateBaseTextsForPage");
//            }
//
//            $transaction->commit();
//            return true;
//        } catch (Exception $x) {
//            Yii::error($x->getMessage(), __METHOD__);
//            $transaction->rollBack();
//            return false;
//        }
//    }

    /**
     * Renvoie la liste des noms des textes associés à $this
     *
     * @return array
     */
    public function baseTextsCodes()
    {
        return ArrayHelper::getColumn($this->baseTexts, 'code');
    }

    /**
     * Renvoie la liste des langues utilisées par les pages associées à $this
     *
     * @return array [Language]
     */
    public function pageLanguages()
    {
        return Language::find()->byBasePageId($this->id)->orderByCode()->all();
    }

    /**
     * Renvoie la liste des codes des langues utilisées par les pages associées à $this
     *
     * @return array [string]
     */
    public function pageLanguagesCodes()
    {
        return ArrayHelper::getColumn($this->pageLanguages(), 'iso_639_code');
    }

    /**
     * Renvoie un tableau présentant la liste des textes associés à cette page, classés par langue.
     * Format du tableau :
     *  [
     *      language.iso_639_code => [
     *          base_texts.code => instance de WebText associée,
     *          ...
     *      ],
     *      ...
     *  ]
     *
     * @return array
     */
    public function relatedTextsByLanguage()
    {
        $out = [];
        $baseTexts = $this->getBaseTexts()->orderBy('base_texts.code')->all();

        /** @var Language $language */
        foreach ($this->pageLanguages() as $language) {
            $out[$language->iso_639_code] = [];

            /** @var BaseText $baseText */
            foreach ($baseTexts as $baseText) {
                /** @var WebText $webText */
                foreach ($baseText->webTexts as $webText) {
                    $out[$language->iso_639_code][$baseText->code] = null;

                    if ($webText->language_id == $language->id) {
                        // On a trouvé le texte correspondant à $language
                        $out[$language->iso_639_code][$baseText->code] = $webText;
                        break;
                    }
                }
            }
        }

        return $out;
    }

    /**
     * Calcule le niveau (profondeur) de $this dans l'arborescence des pages
     *
     * @return int
     */
    public function nodeLevel()
    {
        $level = 0;
        $it = clone $this;

        while (($parentId = $it->parent_page_id) !== null) {
            ++$level;
            $it = BasePage::findOne($parentId);
        }

        return $level;
    }

}

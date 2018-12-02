<?php

namespace app\modules\ephemerides\models;

use app\modules\ephemerides\models\query\CalendarEntryQuery;
use app\modules\hlib\behaviors\SitemapableBehavior;
use app\modules\hlib\behaviors\ImageOwner;
use app\modules\hlib\HLib;
use app\modules\hlib\helpers\hArray;
use app\modules\hlib\helpers\hString;

use app\modules\ephemerides\models\query\CalendarEntryTagQuery;
use Carbon\Carbon;
use SimpleXMLElement;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * This is the model class for table "calendar_entries".
 *
 * @property string $id
 * @property string $event_date
 * @property string $title
 * @property string $body
 * @property string $image
 * @property string $image_caption
 * @property integer $enabled
 * @property integer $notes
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CalendarEntryTag[] $calendarEntryTags
 * @property Tag[] $tags
 *
 * -- propriétés & méthodes fournies par ImageOwner --
 * @property array $thumbnailsSizes
 *
 * @method string getImagesDirectoryPath($absolute = false)
 * @method string getImagePath($thumbnailAlias = null, $absolute = false)
 * @method string setThumbnails()
 * @method string deleteImageFiles()
 * @method string hasImage()
 * @method string getImageUrl($thumbnailAlias = null, $absolute = false)
 * @method string computeImageName($extension = '')
 * @method string resetImagesNames()
 * @method string resizeOriginalImage()
 * -- FIN propriétés & méthodes fournies par ImageOwner --
 */
class CalendarEntry extends ActiveRecord
{
    const DATE_FORMAT_DAY = 'd-m-Y';
    const DATE_FORMAT_MONTH = 'm-Y';
    const DATE_FORMAT_YEAR = 'Y';

    /** @var  int[] */
    private $updatedTagsIds;

    /** @var  int[] */
    private $updatedArticlesIds;

    /** @var  UploadedFile */
    public $uploadedImage;

    /** @var  bool */
    public $deleteImage = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calendar_entries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // formatages préalables
//            ['event_date', 'filter', 'filter' => function ($value) {
//                return hDate::convertDateToSQLFormat($value);
//            }],
            ['event_date', 'filter', 'filter' => function ($value) {
                // en entrée : dd-MM-yyyy ; en sortie : format compatible SQL
                return (new Carbon($value))->toDateString();
            }],
            // validations
            [['event_date', 'title'], 'required'],
//            ['event_date', 'date', 'format' => 'yyyy-MM-dd'],
            // todo_cbn format garanti par le masque pour le moment. Je désactive provisoiement la validation car elle est faite selon le format fr
            // alors que le masque doit être au format us pour être correctement initialisé (cf. ticket #17)
            [['body', 'notes'], 'string'],
            ['enabled', 'boolean'],
            ['deleteImage', 'boolean'],
            [['title', 'image'], 'string', 'max' => 255],
            ['image_caption', 'string', 'max' => 512],
            ['uploadedImage', 'file', 'extensions' => 'png, jpg, jpeg, gif'], // seuls formats reconnus par le driver GD
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
                'filename' => 'sitemap.entries.xml',
                'callback' => function (SimpleXMLElement $xml) {
                    foreach (CalendarEntry::find()->enabled()->all() as $entry) {
                        $url = $xml->addChild('url');
                        $url->addChild('loc', Html::encode(Url::to(['/calendar-entries/show', 'id' => $entry->id, 'slug' => $entry->getSlug()], true)));
                        $url->addChild('changefreq', 'monthly');
                        $url->addChild('lastmod', $entry->updated_at);
                    }

                    return $xml;
                }
            ],
            [
                'class' => ImageOwner::class,
                'imagesDirectoryName' => Yii::$app->params['calendarEntry']['images']['webDirectory'],
                'originalImageSize' => Yii::$app->params['calendarEntry']['images']['originalSize'],
                'thumbnailsSizes' => Yii::$app->params['calendarEntry']['images']['thumbnailsSizes'],
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
            'event_date' => Yii::t('labels', 'Event Date'),
            'title' => HLib::t('labels', 'Title'),
            'body' => Yii::t('labels', 'Body'),
            'image' => HLib::t('labels', 'Image'),
            'image_caption' => Yii::t('labels', 'Image caption'),
            'uploadedImage' => HLib::t('labels', 'Image'),
            'enabled' => HLib::t('labels', 'Enabled'),
            'notes' => Yii::t('labels', 'Notes'),
            'created_at' => HLib::t('labels', 'Created At'),
            'updated_at' => HLib::t('labels', 'Updated At'),
            'tags' => Yii::t('labels', 'Tags'),
            'deleteImage' => Yii::t('labels', 'Delete this image'),
        ];
    }

    /**
     * Renvoie une instance de l'ActiveQuery associée à cette classe.
     *
     * @inheritdoc
     * @return CalendarEntryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CalendarEntryQuery();
    }

    /**
     * Déclaration de la relation 1-n avec la table d'association calendar_entry_tag
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCalendarEntryTags()
    {
        return $this->hasMany(CalendarEntryTag::class, ['calendar_entry_id' => 'id']);
    }

    /**
     * Déclaration de la relation n-n avec la table tags
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->viaTable('calendar_entry_tag', ['calendar_entry_id' => 'id']);
    }

    /**
     * Renvoie la liste des identifiants des tags associés à $this
     *
     * @return array
     */
    public function getTagsIds()
    {
        return hArray::getColumn($this->tags, 'id');
    }

    /**
     * Renvoie la liste des noms des tags associés à $this
     *
     * @return array
     */
    public function getTagsNames()
    {
        return hArray::getColumn($this->tags, 'label');
    }

    /**
     * Renvoie la liste des identifiants des articles associés à $this
     *
     * @return array
     */
    public function getArticlesIds()
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return hArray::getColumn($this->articles, 'id');
    }

    /**
     * Renvoie la liste des noms des tags associés à $this
     *
     * @return array
     */
    public function getArticlesTitles()
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return hArray::getColumn($this->articles, 'title');
    }

    /**
     * Assignation en masse des attributs avec prise en compte de la liste des ids des tags & des articles
     * todo_cbn cf. issue #20
     *
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        $this->updatedTagsIds = hArray::getValue($data, 'CalendarEntry.tags');
        if ($this->updatedTagsIds === "") {
            $this->updatedTagsIds = [];
        }

        $this->updatedArticlesIds = hArray::getValue($data, 'CalendarEntry.articles');
        if ($this->updatedArticlesIds === "") {
            $this->updatedArticlesIds = [];
        }

        return parent::load($data, $formName);
    }

    /**
     * Mise à jour de l'objet.
     * Si $saveRelated vaut 'true', les tables d'association sont mises à jour en même temps.
     *
     * @param bool|true $runValidation
     * @param null $attributeNames
     * @param bool|false $saveRelated
     * @return bool
     * @throws Exception
     */
    public function save($runValidation = true, $attributeNames = null, $saveRelated = false)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!parent::save($runValidation, $attributeNames)) {
                throw new Exception('Erreur sur parent::save()');
            }

            if ($saveRelated && !CalendarEntryTagQuery::updateTagsForCalendarEntry($this->getTagsIds(), $this->updatedTagsIds, $this->id)) {
                throw new Exception("Erreur sur CalendarEntryTag::updateTagsForCalendarEntry()");
            }

            $transaction->commit();
            return true;
        } catch (Exception $x) {
            Yii::error($x->getMessage(), __METHOD__);
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * Calcule un 'slug' pour cette éphéméride.
     * Le format du slug est : date de l'événement (yyyy-mm-dd) - espace - titre
     *
     * @return string
     */
    public function getSlug()
    {
        // $this->event_date est au format SQL : 2010-08-20 00:00:00
        // Pour le slug, seule la partie 'date' nous intéresse, la partie 'heure' doit être supprimée
        $date = Carbon::createFromFormat('Y-m-d', $this->event_date);
        return hString::slugify($date->toDateString() . ' ' . $this->title);
    }

    /**
     * @return int
     */
    public function year()
    {
        return Carbon::createFromFormat('Y-m-d', $this->event_date)->year;
    }

    /**
     * @return int
     */
    public function month()
    {
        return Carbon::createFromFormat('Y-m-d', $this->event_date)->month;
    }

    /**
     * @return int
     */
    public function day()
    {
        return Carbon::createFromFormat('Y-m-d', $this->event_date)->day;
    }

}

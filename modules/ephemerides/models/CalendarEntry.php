<?php /** @noinspection PhpMissingFieldTypeInspection */

/** @noinspection PhpUnused */

namespace app\modules\ephemerides\models;

use app\modules\ephemerides\EphemeridesModule;
use app\modules\ephemerides\lib\enums\Domaine;
use app\modules\ephemerides\models\query\CalendarEntryQuery;
use app\modules\hlib\behaviors\SitemapableBehavior;
use app\modules\hlib\behaviors\ImageOwner;
use app\modules\hlib\helpers\hArray;
use app\modules\hlib\helpers\hString;
use app\modules\hlib\interfaces\EnabledInterface;
use Carbon\Carbon;
use SimpleXMLElement;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * This is the model class for table "calendar_entries".
 *
 * @property string $id
 * @property string $event_date
 * @property string $title
 * @property string $description
 * @property string $domaine @see enum Domaine
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
 * -- DEBUT propriétés & méthodes fournies par ImageOwner --
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
class CalendarEntry extends ActiveRecord implements EnabledInterface
{
    const DATE_FORMAT_DAY = 'd-m-Y';
    const DATE_FORMAT_MONTH = 'm-Y';
    const DATE_FORMAT_YEAR = 'Y';

    /** @var  UploadedFile */
    public $uploadedImage;

    /** @var  bool */
    public bool $deleteImage = false;

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'calendar_entry';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            // date
//            ['event_date', 'filter', 'filter' => function ($value) {
//                return hDate::convertDateToSQLFormat($value);
//            }],
            ['event_date',
                'filter', 'filter' => function ($value) {
                // en entrée : dd-MM-yyyy ; en sortie : format compatible SQL
                return (new Carbon($value))->toDateString();
            }],
            // required
            [['title', 'event_date'],
                'required'],
            //string
            [['title', 'image', 'image_caption'],
                'filter', 'filter' => [hString::class, 'sanitize']],
            // boolean
            [['enabled', 'deleteImage'],
                'boolean'],
            // enum
            [['domaine'],
                'in', 'range' => Domaine::getKeys()],
            //
            [['uploadedImage'],
                'file', 'extensions' => 'png, jpg, jpeg, gif'], // seuls formats reconnus par le driver GD
            // safe
            [['description', 'notes', 'body'],
                'safe']
        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
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
                'imagesDirectoryName' => EphemeridesModule::getInstance()->images['webDirectory'],
                'originalImageSize' => EphemeridesModule::getInstance()->images['originalSize'],
                'thumbnailsSizes' => EphemeridesModule::getInstance()->images['thumbnailsSizes'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'event_date' => 'Date',
            'title' => "Titre",
            'description' => "Description",
            'body' => "Article",
            'image' => "Image",
            'image_caption' => "Légende",
            'uploadedImage' => "Image",
            'enabled' => "Activé",
            'notes' => "Notes",
            'created_at' => "Date création",
            'updated_at' => "Date maj",
            'tags' => "Catégories",
            'deleteImage' => "Supprimer cette image",
        ];
    }

    /**
     * Renvoie une instance de l'ActiveQuery associée à cette classe.
     *
     * @inheritdoc
     * @return CalendarEntryQuery the active query used by this AR class.
     */
    public static function find(): CalendarEntryQuery
    {
        return new CalendarEntryQuery();
    }

    /**
     * Déclaration de la relation 1-n avec la table d'association calendar_entry_tag
     *
     * @return ActiveQuery
     */
    public function getCalendarEntryTags(): ActiveQuery
    {
        return $this->hasMany(CalendarEntryTag::class, ['calendar_entry_id' => 'id']);
    }

    /**
     * Déclaration de la relation n-n avec la table tags
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->viaTable('calendar_entry_tag', ['calendar_entry_id' => 'id']);
    }

    /////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Renvoie la liste des identifiants des tags associés à $this
     *
     * @return array
     */
    public function getTagsIds(): array
    {
        return hArray::getColumn($this->tags, 'id');
    }

    /**
     * Renvoie la liste des noms des tags associés à $this
     *
     * @return array
     */
    public function getTagsNames(): array
    {
        return hArray::getColumn($this->tags, 'label');
    }

    /**
     * Calcule un 'slug' pour cette éphéméride.
     * Le format du slug est : date de l'événement (yyyy-mm-dd) - espace - titre
     *
     * @return string
     */
    public function getSlug(): string
    {
        // $this->event_date est au format SQL : 2010-08-20 00:00:00
        // Pour le slug, seule la partie 'date' nous intéresse, la partie 'heure' doit être supprimée
        $date = Carbon::createFromFormat('Y-m-d', $this->event_date);
        return Inflector::slug($date->toDateString() . ' ' . $this->title);
    }

    /**
     * @return int
     */
    public function year(): int
    {
        $date = Carbon::createFromFormat('Y-m-d', $this->event_date);
        return $date->year;
    }

    /**
     * @return int
     */
    public function month(): int
    {
        $date = Carbon::createFromFormat('Y-m-d', $this->event_date);
        return $date->month;
    }

    /**
     * @return int
     */
    public function day(): int
    {
        $date = Carbon::createFromFormat('Y-m-d', $this->event_date);
        return $date->day;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}

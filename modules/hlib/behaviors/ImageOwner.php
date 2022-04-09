<?php /** @noinspection PhpUnused */

namespace app\modules\hlib\behaviors;

use app\modules\hlib\helpers\hFile;
use app\modules\hlib\helpers\hImage;
use app\modules\hlib\HLib;
use Exception;
use Yii;
use yii\base\Behavior;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\UnknownPropertyException;
use yii\helpers\ArrayHelper;


/**
 * Class ImageOwner
 *
 *
 * @package app\modules\hlib\behaviors
 */
class ImageOwner extends Behavior
{
    /** @var string */
    public string $idField = 'id';

    /** @var string */
    public string $imageField = 'image';

    /** @var string */
    public string $slugField = 'slug';

    /** @var string */
    public string $imagesDirectoryName = 'images';

    /** @var string */
    public string $publicDirectory;

    /** @var array */
    public array $originalImageSize;

    /** @var array */
    public array $thumbnailsSizes;

    /**
     * @param Component $owner
     * @throws InvalidConfigException
     */
    public function attach($owner): void
    {
        if (!is_a($owner, 'yii\db\BaseActiveRecord')) {
            throw new InvalidConfigException('Le possesseur de ce behavior doit descendre de yii\db\BaseActiveRecord');
        }

        parent::attach($owner);
    }

    /**
     * @return bool
     * @throws UnknownPropertyException
     */
    public function hasImage(): bool
    {
        return $this->owner->__get($this->imageField) != "";
    }

    /**
     * Renvoie le nom que devrait avoir l'image associée à $this.
     * L'extension doit être passée en argument si on en désire une.
     *
     * @param string $extension
     * @return string
     * @throws UnknownPropertyException
     */
    public function computeImageName(string $extension = ''): string
    {
        $imageName = $this->owner->__get($this->slugField) . '-' . $this->owner->__get($this->idField);
        if ($extension != '') {
            $imageName .= '.' . $extension;
        }

        return $imageName;
    }

    /**
     * Renvoie le chemin d'accès au répertoire où sont stockées les images de l'objet.
     * Il s'agit ici d'un chemin relatif par rapport au répertoire public
     *
     * @param bool $absolute
     * @return string
     * @throws InvalidConfigException
     */
    public function getImagesDirectoryPath(bool $absolute = false): string
    {
        // NB : l'alias @webroot est inconnu pour les applications console, on utilise donc @app + nom du répertoire public
        $pathPrefix = $absolute ? sprintf('%s/%s/', Yii::getAlias('@app'), $this->retrievePublicDirectoryName()) : '';
        return $pathPrefix . Yii::$app->params['images']['webDirectory'] . '/' . $this->imagesDirectoryName;
    }

    /**
     * Renvoie le chemin d'accès au fichier image
     * Il s'agit ici d'un chemin relatif par rapport au répertoire public
     * Si $thumbnailAlias est renseigné avec une des clés déclarées dans self::$thumbnail_size, on renvoie l'url de cette vignette.
     *
     * @param ?string $thumbnailAlias Alias indiquant la taille demandée pour la vignette. Laisser cet argument à null si on veut l'image d'origine.
     * @param bool $absolute
     * @return string
     * @throws Exception
     * @throws UnknownPropertyException
     */
    public function getImagePath(?string $thumbnailAlias = null, bool $absolute = false): string
    {
        if (!is_null($thumbnailAlias)) {
            // On a demandé une vignette
            $thumbnailSize = $this->getThumbnailSize($thumbnailAlias);
            $subdirectory = self::getThumbnailsDirectoryName($thumbnailSize['width'], $thumbnailSize['height']);
            return $this->getImagesDirectoryPath($absolute) . '/' . $subdirectory . '/' . $this->owner->__get($this->imageField);
        } else {
            // On a demandé l'image originale
            return $this->getImagesDirectoryPath($absolute) . '/' . $this->owner->__get($this->imageField);
        }
    }

    /**
     * Renvoie l'url du répertoire où sont stockées les images de l'objet.
     * Il s'agit ici d'un chemin relatif par rapport au répertoire public
     *
     * @param bool $absolute
     * @return string
     * @throws InvalidConfigException
     */
    public function getImagesDirectoryUrl(bool $absolute = false): string
    {
        $pathPrefix = $absolute ? Yii::getAlias('@web') . '/' : "";
        return $pathPrefix . $this->getImagesDirectoryPath();
    }

    /**
     * Renvoie l'URL de l'image. Si $thumbnailAlias est renseigné avec une des clés déclarées dans self::$thumbnail_size, on renvoie plutôt
     * l'url de la vignette correspondant à cette taille.
     * NB :
     *  - chemin absolu => on préfixe avec le nom de domaine complet
     *  - chemin relatif => on préfixe avec le séparateur '/' ce qui suppose que les images sont toujours dans un sous-répertoire par rapport
     *  à la page web qui est lue. Ex : page web dans public_html et images dans public_html/images.
     *
     * @param ?string $thumbnailAlias Clé du tableau self::$thumbnail_size de l'objet implémentant ce trait. Si ce paramèetre erste à null, c'est l'image
     * de référence qui sera renvoyée.
     * @param bool $absolute true => URL absolue, false (défaut) => URL relative
     * @return string
     * @throws Exception
     * @throws UnknownPropertyException
     */
    public function getImageUrl(?string $thumbnailAlias = null, bool $absolute = false): string
    {
        $pathPrefix = $absolute ? Yii::getAlias('@web') . '/' : "/";
        return $pathPrefix . $this->getImagePath($thumbnailAlias);
    }

    /**
     * Retaille l'image originale de l'objet.
     *
     * @throws UnknownPropertyException
     * @throws InvalidConfigException
     */
    public function resizeOriginalImage(): void
    {
        hImage::configure()
            ->make($this->getImagesDirectoryPath(true) . '/' . $this->owner->__get($this->imageField))
            ->widen($this->originalImageSize['width'])
            ->save()
            ->destroy();
    }

    /**
     * Fabrique les vignettes prévues par l'application
     *
     * @throws UnknownPropertyException
     * @throws Exception
     */
    public function setThumbnails(): void
    {
        $imagesDirectoryPath = $this->getImagesDirectoryPath(true);
        $originalImagePath = $imagesDirectoryPath . '/' . $this->owner->__get($this->imageField);
        foreach ($this->thumbnailsSizes as $size) {
            $subdirectory = self::getThumbnailsDirectoryName($size['width'], $size['height']);
            $thumbnailPath = $imagesDirectoryPath . '/' . $subdirectory . '/' . $this->owner->__get($this->imageField);
            hFile::createDirectory(dirname($thumbnailPath), 0744);
            hImage::configure()
                ->make($originalImagePath)
                ->widen($size['width'])
                ->save($thumbnailPath)
                ->destroy();
        }
    }

    /**
     * Suppression de toutes les images associées à l'objet : image originale + vignettes
     *
     * @throws UnknownPropertyException
     * @throws Exception
     */
    public function deleteImageFiles(): void
    {
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        if ($this->owner->hasImage()) {
            $imagesDirectoryPath = $this->getImagesDirectoryPath(true);
            $originalImagePath = $imagesDirectoryPath . '/' . $this->owner->__get($this->imageField);
            hFile::delete($originalImagePath);

            foreach ($this->thumbnailsSizes as $size) {
                $subdirectory = self::getThumbnailsDirectoryName($size['width'], $size['height']);
                $thumbnailPath = $imagesDirectoryPath . '/' . $subdirectory . '/' . $this->owner->__get($this->imageField);
                hFile::delete($thumbnailPath);
            }

            $this->owner->__set($this->imageField, null);
        }
    }

    /**
     * Renommage des images de l'objet : on renomme non seulement l'image principale mais aussi toutes les vignettes
     * Le nouveau nom de l'image doit avoir été enregistré dans $this. L'ancien nom se trouve dans getOriginal()
     *
     * @throws UnknownPropertyException
     * @throws Exception
     */
    public function resetImagesNames(): void
    {
        $imagesDirectoryPath = $this->getImagesDirectoryPath(true);
        // getOldAttribute est une méthode de l'ActiveRecord et ce behavior ne doit être attaché qu'à des ActiveRecords
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $originalImageName = $this->owner->getOldAttribute('image');

        // On renomme l'image originale...
        $originalImagePath = $imagesDirectoryPath . '/' . $originalImageName;
        $newImagePath = $this->getImagePath(null, true);
        rename($originalImagePath, $newImagePath);

        // ... puis les vignettes
        foreach ($this->thumbnailsSizes as $sizeArray) {
            $subdirectory = self::getThumbnailsDirectoryName($sizeArray['width'], $sizeArray['height']);
            $originalThumbnailPath = $imagesDirectoryPath . '/' . $subdirectory . '/' . $originalImageName;
            $newThumbnailPath = $imagesDirectoryPath . '/' . $subdirectory . '/' . $this->owner->__get($this->imageField);
            rename($originalThumbnailPath, $newThumbnailPath);
        }
    }

    /**
     * Renvoie la taille de la vignette $alias
     *
     * @param string $alias
     * @return array ['width' => 555, 'height' => 444], ...]
     * @throws Exception
     */
    public function getThumbnailSize(string $alias): array
    {
        $out = ArrayHelper::getValue($this->thumbnailsSizes, $alias);
        if (!$out) {
            throw new Exception("Alias inconnu pour la vignette : $alias");
        }

        return $out;
    }

    /**
     * Renvoie le nom du sous-répertoire hébergeant les vignettes de la taille indiquée
     *
     * @param int $thumbnailWidth
     * @param int $thumbnailHeight
     * @return string
     */
    public static function getThumbnailsDirectoryName(int $thumbnailWidth, int $thumbnailHeight = 0): string
    {
        return $thumbnailWidth . 'x' . $thumbnailHeight;
    }

    /**
     * Renvoie le nom du répertoire web del'application.
     * S'il est renseigné dans la configuration de $this, on prned ce qui a été paramétré.
     * Sinon, on prend le premier répertoire trouvé parmi public_html, www et web.
     * Si aucun répertoire n'est identifiable, on lance une exception
     *
     * @return string
     * @throws InvalidConfigException
     */
    private function retrievePublicDirectoryName(): string
    {
        if (isset($this->publicDirectory)) {
            return $this->publicDirectory;
        }

        $dirnames = ['public_html', 'www', 'web'];
        $appDir = Yii::getAlias('@app');
        foreach ($dirnames as $dirname) {
            if (is_dir("$appDir/$dirname")) {
                return $dirname;
            }
        }

        throw new InvalidConfigException(HLib::t('messages', "No valid public directory for " . __CLASS__));
    }
}
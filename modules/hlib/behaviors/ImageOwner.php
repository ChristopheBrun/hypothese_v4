<?php

namespace app\modules\hlib\behaviors;

use app\modules\hlib\helpers\hFile;
use app\modules\hlib\helpers\hImage;
use Yii;
use yii\base\Behavior;
use yii\base\Exception;
use yii\base\InvalidConfigException;
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
    public $idField = 'id';

    /** @var string */
    public $imageField = 'image';

    /** @var string */
    public $slugField = 'slug';

    /** @var string */
    public $imagesDirectoryName = 'images';

    /** @var string */
    public $originalImageSize;

    /** @var array */
    public $thumbnailsSizes;

    /**
     * @param \yii\base\Component $owner
     * @throws InvalidConfigException
     */
    public function attach($owner)
    {
        if (!is_a($owner, 'yii\db\BaseActiveRecord')) {
            throw new InvalidConfigException('Le possesseur de ce behavior doit descendre de yii\db\BaseActiveRecord');
        }

        parent::attach($owner);
    }

    /**
     * @return bool
     */
    public function hasImage()
    {
        $field = $this->imageField;
        return $this->owner->$field != "";
    }

    /**
     * Renvoie le nom que devrait avoir l'image associée à $this.
     * L'extension doit être passée en argument si on en désire une.
     *
     * @param string $extension
     * @return string
     */
    public function computeImageName($extension = '')
    {
        $slugField = $this->slugField;
        $idField = $this->idField;
        $imageName = $this->owner->$slugField . '-' . $this->owner->$idField;
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
     */
    public function getImagesDirectoryPath($absolute = false)
    {
        // NB : l'alias @webroot est inconnu pour les applications console, on utilise donc @app + nom du répertoire public
        $pathPrefix = $absolute ? Yii::getAlias('@app') . '/public_html/' : "";
        return $pathPrefix . Yii::$app->params['images']['webDirectory'] . '/' . $this->imagesDirectoryName;
    }

    /**
     * Renvoie le chemin d'accès au fichier image
     * Il s'agit ici d'un chemin relatif par rapport au répertoire public
     * Si $thumbnailAlias est renseigné avec une des clés déclarées dans self::$thumbnail_size, on renvoie l'url de cette vignette.
     *
     * @param string $thumbnailAlias Alias indiquant la taille demandée pour la vignette. Laisser cet argument à null si on veut l'image d'origine.
     * @param bool $absolute
     * @return string
     * @throws Exception
     */
    public function getImagePath($thumbnailAlias = null, $absolute = false)
    {
        $imageField = $this->imageField;
        if (!is_null($thumbnailAlias)) {
            // On a demandé une vignette
            $thumbnailSize = $this->getThumbnailSize($thumbnailAlias);
            $subdirectory = self::getThumbnailsDirectoryName($thumbnailSize['width'], $thumbnailSize['height']);
            return $this->getImagesDirectoryPath($absolute) . '/' . $subdirectory . '/' . $this->owner->$imageField;
        } else {
            // On a demandé l'image originale
            return $this->getImagesDirectoryPath($absolute) . '/' . $this->owner->$imageField;
        }
    }

    /**
     * Renvoie l'url du répertoire où sont stockées les images de l'objet.
     * Il s'agit ici d'un chemin relatif par rapport au répertoire public
     *
     * @param bool $absolute
     * @return string
     */
    public function getImagesDirectoryUrl($absolute = false)
    {
        $pathPrefix = $absolute ? Yii::getAlias('@web') . '/' : "";
        return $pathPrefix . $this->getImagesDirectoryPath(false);
    }

    /**
     * Renvoie l'URL de l'image. Si $thumbnailAlias est renseigné avec une des clés déclarées dans self::$thumbnail_size, on renvoie plutôt
     * l'url de la vignette correspondant à cette taille.
     * NB :
     *  - chemin absolu => on préfixe avec le nom de domaine complet
     *  - chemin relatif => on préfixe avec le séparateur '/' ce qui suppose que les images sont toujours dans un sous-répertoire par rapport
     *  à la page web qui est lue. Ex : page web dans public_html et images dans public_html/images.
     *
     * @param string $thumbnailAlias Clé du tableau self::$thumbnail_size de l'objet implémentant ce trait. Si ce paramèetre erste à null, c'est l'image
     * de référence qui sera renvoyée.
     * @param bool $absolute true => URL absolue, false (défaut) => URL relative
     * @return string
     * @throws Exception
     */
    public function getImageUrl($thumbnailAlias = null, $absolute = false)
    {
        $pathPrefix = $absolute ? Yii::getAlias('@web') . '/' : "/";
        return $pathPrefix . $this->getImagePath($thumbnailAlias, false);
    }

    /**
     * Retaille l'image originale de l'objet.
     */
    public function resizeOriginalImage()
    {
        $imageField = $this->imageField;
        /** @noinspection PhpIllegalStringOffsetInspection */
        hImage::configure()
            ->make($this->getImagesDirectoryPath(true) . '/' . $this->owner->$imageField)
            ->widen($this->originalImageSize['width'])
            ->save()
            ->destroy();
    }

    /**
     * Fabrique les vignettes prévues par l'application
     * @throws Exception
     */
    public function setThumbnails()
    {
        $imageField = $this->imageField;
        $imagesDirectoryPath = $this->getImagesDirectoryPath(true);
        $originalImagePath = $imagesDirectoryPath . '/' . $this->owner->$imageField;
        foreach ($this->thumbnailsSizes as $alias => $size) {
            $subdirectory = self::getThumbnailsDirectoryName($size['width'], $size['height']);
            $thumbnailPath = $imagesDirectoryPath . '/' . $subdirectory . '/' . $this->owner->$imageField;
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
     * @throws \Exception
     */
    public function deleteImageFiles()
    {
        $imageField = $this->imageField;
        $imagesDirectoryPath = $this->getImagesDirectoryPath(true);
        $originalImagePath = $imagesDirectoryPath . '/' . $this->owner->$imageField;
        hFile::delete($originalImagePath);

        foreach ($this->thumbnailsSizes as $alias => $size) {
            $subdirectory = self::getThumbnailsDirectoryName($size['width'], $size['height']);
            $thumbnailPath = $imagesDirectoryPath . '/' . $subdirectory . '/' . $this->owner->$imageField;
            hFile::delete($thumbnailPath);
        }

        $this->owner->$imageField = null;
    }

    /**
     * Renommage des images de l'objet : on renomme non seulement l'image principale mais aussi toutes les vignettes
     * Le nouveau nom de l'image doit avoir été enregistré dans $this. L'ancien nom se trouve dans getOriginal()
     * @throws Exception
     */
    public function resetImagesNames()
    {
        $imagesDirectoryPath = $this->getImagesDirectoryPath(true);
        /** @noinspection PhpUndefinedMethodInspection */
        // getOldAttribute est une méthode de l'ActiveRecord et ce behavior ne doit être attaché qu'à des ActiveRecords
        $originalImageName = $this->owner->getOldAttribute('image');

        // On renomme l'image originale...
        $originalImagePath = $imagesDirectoryPath . '/' . $originalImageName;
        $newImagePath = $this->getImagePath(null, true);
        rename($originalImagePath, $newImagePath);

        // ... puis les vignettes
        $imageField = $this->imageField;
        foreach ($this->thumbnailsSizes as $alias => $sizeArray) {
            $subdirectory = self::getThumbnailsDirectoryName($sizeArray['width'], $sizeArray['height']);
            $originalThumbnailPath = $imagesDirectoryPath . '/' . $subdirectory . '/' . $originalImageName;
            $newThumbnailPath = $imagesDirectoryPath . '/' . $subdirectory . '/' . $this->owner->$imageField;
            rename($originalThumbnailPath, $newThumbnailPath);
        }
    }

    /**
     * Renvoie la taille de la vignette $alias
     *
     * @param $alias
     * @return array ['width' => 555, 'height' => 444], ...]
     * @throws Exception
     */
    public function getThumbnailSize($alias)
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
    public static function getThumbnailsDirectoryName($thumbnailWidth, $thumbnailHeight = 0)
    {
        return $thumbnailWidth . 'x' . $thumbnailHeight;
    }
}
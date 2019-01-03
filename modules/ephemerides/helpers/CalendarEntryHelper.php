<?php

namespace app\modules\ephemerides\helpers;

use app\modules\ephemerides\models\CalendarEntry;
use app\modules\hlib\behaviors\ImageOwner;
use app\modules\ephemerides\models\query\CalendarEntryQuery;
use yii\helpers\FileHelper;


/**
 * Class CalendarEntryHelper
 * @package app\helpers
 */
class CalendarEntryHelper
{
    /**
     * Supprime les images orphelines du répertoire images/calendar_entries et de ses sous-répertoires.
     * Régénère les vignettes si nécessaire, met le champ 'image' à NULL dans la base si une image principale est introuvable
     * NB :
     * - Si un alias de taille a été ajouté, les nouvelles vignettes seront générées.
     * -->> Par contre, si un alias de taille a été supprimé, le dossier correspondant sera ignoré. Il faut le supprimer à la main.
     *
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function resetImages()
    {
        $model = new CalendarEntry();
        $mainDirectory = $model->getImagesDirectoryPath(true);

        $directories = [];
        $directories[] = $mainDirectory;
        foreach ($model->thumbnailsSizes as $key => $size) {
            $directories[] = $mainDirectory . '/' . ImageOwner::getThumbnailsDirectoryName($size['width'], $size['height']);
        }

        // On liste les fichiers images présents sur le serveur dans le dossier des éphémérides
        $files = FileHelper::findFiles($mainDirectory);
        $deleteFiles = [];
        foreach($files as $file) {
            $deleteFiles[FileHelper::normalizePath($file)] = true;
        }

        $addFiles = [];

        // On vérifie quelles sont les images effectivement associées aux éphémérides
        foreach (CalendarEntryQuery::getImages() as $row) {
            $createImage = false;
            foreach($directories as $dir) {
                $file = FileHelper::normalizePath($dir . '/' . $row['image']);
                if(array_key_exists($file, $deleteFiles)) {
                    // image bien associée : on ne doit pas la supprimer
                    $deleteFiles[$file] = false;
                }
                else {
                    // image manquant sur le disque : il faudra régénérer les images pour cette éphéméride
                    $createImage = true;
                }
            }

            if($createImage) {
                $addFiles[] = $row['id'];
            }
        }

        // Suppression de toutes les images non référencées
        foreach($deleteFiles as $file => $delete) {
            if($delete) {
                unlink($file);
            }
        }

        // Mise à jour des vignettes manquantes ou ménage
        foreach($addFiles as $entryId) {
            /** @var CalendarEntry $entry */
            $entry = CalendarEntry::findOne($entryId);
            $mainImage = $entry->getImagePath(null, true);
            if(is_file($mainImage)) {
                $entry->setThumbnails();
            }
            else {
                $entry->deleteImageFiles();
                CalendarEntryQuery::setImageAsNull($entry->id);
            }
        }

        return true;
    }

}
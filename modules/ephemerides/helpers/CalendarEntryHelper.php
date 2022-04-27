<?php

namespace app\modules\ephemerides\helpers;

use app\modules\ephemerides\models\CalendarEntry;
use app\modules\hlib\behaviors\ImageOwner;
use app\modules\ephemerides\models\query\CalendarEntryQuery;
use app\modules\hlib\helpers\DateHelper;
use DateTime;
use yii\db\Exception;
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
     * @throws Exception
     */
    public static function resetImages(): bool
    {
        $model = new CalendarEntry();
        $mainDirectory = $model->getImagesDirectoryPath(true);

        $directories = [];
        $directories[] = $mainDirectory;
        foreach ($model->thumbnailsSizes as $size) {
            $directories[] = $mainDirectory . '/' . ImageOwner::getThumbnailsDirectoryName($size['width'], $size['height']);
        }

        // On liste les fichiers images présents sur le serveur dans le dossier des éphémérides
        $files = FileHelper::findFiles($mainDirectory);
        $deleteFiles = [];
        foreach ($files as $file) {
            $deleteFiles[FileHelper::normalizePath($file)] = true;
        }

        $addFiles = [];

        // On vérifie quelles sont les images effectivement associées aux éphémérides
        foreach (CalendarEntryQuery::getImages() as $row) {
            $createImage = false;
            foreach ($directories as $dir) {
                $file = FileHelper::normalizePath($dir . '/' . $row['image']);
                if (array_key_exists($file, $deleteFiles)) {
                    // image bien associée : on ne doit pas la supprimer
                    $deleteFiles[$file] = false;
                } else {
                    // image manquant sur le disque : il faudra régénérer les images pour cette éphéméride
                    $createImage = true;
                }
            }

            if ($createImage) {
                $addFiles[] = $row['id'];
            }
        }

        // Suppression de toutes les images non référencées
        foreach ($deleteFiles as $file => $delete) {
            if ($delete) {
                unlink($file);
            }
        }

        // Mise à jour des vignettes manquantes ou ménage
        foreach ($addFiles as $entryId) {
            /** @var CalendarEntry $entry */
            $entry = CalendarEntry::findOne($entryId);
            $mainImage = $entry->getImagePath(null, true);
            if (is_file($mainImage)) {
                $entry->setThumbnails();
            } else {
                $entry->deleteImageFiles();
                CalendarEntryQuery::setImageAsNull($entry->id);
            }
        }

        return true;
    }

    /**
     * @param ?DateTime $fromDate
     * @return string Date pour les éphémérides, au format 'mm-dd'
     * @throws Exception
     */
    public static function findNextDateWithNoEntries(?DateTime $fromDate = null): string
    {
        if (is_null($fromDate)) {
            $fromDate = new DateTime();
        }
        $entriesDates = CalendarEntryQuery::monthAndDaysWithEnabledEntries();
        $today = $fromDate->format('m-d');
        $dateBeforeToday = '';
        $dateAfterToday = '';
        for ($month = 1; $month < 13; ++$month) {
            if ($dateAfterToday && $dateBeforeToday) {
                break;
            }

            for ($day = 1; $day < 32; ++$day) {
                if (!DateHelper::isValidCalendarDate($month, $day)) {
                    continue;
                }

                $dateStr = sprintf('%02d-%02d', $month, $day);
                if (!in_array($dateStr, $entriesDates)) {
                    if ($dateStr < $today && $dateBeforeToday === '') {
                        $dateBeforeToday = $dateStr;
                    } elseif ($dateStr >= $today && $dateAfterToday === '') {
                        $dateAfterToday = $dateStr;
                    }
                }
            }
        }

        return $dateAfterToday ?: $dateBeforeToday;
    }
}
<?php

namespace app\modules\hlib\helpers;

use Yii;


/**
 * Class DateHelper
 * @package app\modules\ia\helpers
 */
class DateHelper
{
    /**
     * Renvoie la date exprimée en langage naturel si possible, renvoie la date au format SQL classique sinon.
     * NB : la date en langage naturel utilise les fichiers i18n du module dektrium/yii2-user
     * @todo_cbn Récupérer ces chaines de traduction dans nos propres fichiers i18n pour éliminer la dépendance
     *
     * @param int $time
     * @return false|string
     */
    public static function timestampToNaturalDate($time)
    {
        if (extension_loaded('intl')) {
            return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$time]);
        }
        else {
            return date('Y-m-d G:i:s', $time);
        }

    }

    /**
     * Renvoie la date exprimée en langage naturel si possible, renvoie la date au format SQL classique sinon.
     * NB : la date en langage naturel utilise les fichiers i18n du module dektrium/yii2-user
     * @todo_cbn Récupérer ces chaines de traduction dans nos propres fichiers i18n pour éliminer la dépendance
     *
     * @param string $date
     * @return false|string
     */
    public static function dateSQLToNaturalDate($date)
    {
        if (extension_loaded('intl')) {
            return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [strtotime($date)]);
        }
        else {
            return date('Y-m-d G:i:s', strtotime($date));
        }
    }

    /**
     * Convertit une date FR en date SQL : 12-03-2005 >> 2005-03-12
     * Renvoie false si la date à traiter n'est pas au bon format
     *
     * @param string $date
     * @param string $separator
     * @return bool|string
     */
    public static function dateFRToSQL($date, $separator = '/')
    {
        $matches = [];
        if (preg_match("#(\d{2})$separator(\d{2})$separator(\d{4})#", $date, $matches)) {
            $date = [$matches[3], $matches[2], $matches[1]];
            return implode('-', $date);
        }

        return false;
    }

    /**
     * Convertit une date SQL en date FR : 2005/03/12 >> 12-03-2005
     * Renvoie false si la date à traiter n'est pas au bon format
     *
     * @param string $date
     * @param string $separator
     * @return bool|string
     */
    public static function dateSQLToFR($date, $separator = '/')
    {
        $matches = [];
        if (preg_match("#(\d{4}).(\d{2}).(\d{2})#", $date, $matches)) {
            $date = [$matches[3], $matches[2], $matches[1]];
            return implode($separator, $date);
        }

        return false;
    }
}
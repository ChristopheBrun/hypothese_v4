<?php

namespace app\modules\hlib\helpers;

use app\modules\ephemerides\helpers\MonthFR;
use DateTimeImmutable;
use Exception;
use IntlDateFormatter;
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
     * @param int $time
     * @return false|string
     */
    public static function timestampToNaturalDate(int $time): string|false
    {
        if (extension_loaded('intl')) {
            return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$time]);
        } else {
            return date('Y-m-d G:i:s', $time);
        }

    }

    /**
     * Renvoie la date exprimée en langage naturel si possible, renvoie la date au format SQL classique sinon.
     * NB : la date en langage naturel utilise les fichiers i18n du module dektrium/yii2-user
     * @todo_cbn Récupérer ces chaines de traduction dans nos propres fichiers i18n pour éliminer la dépendance
     * @param string $date
     * @return false|string
     */
    public static function dateSQLToNaturalDate(string $date): false|string
    {
        if (extension_loaded('intl')) {
            return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [strtotime($date)]);
        } else {
            return date('Y-m-d G:i:s', strtotime($date));
        }
    }

    /**
     * Prend une date au format SL (1971-04-16) et la renvoie au format fr localisé (16 avril 1971)
     * @throws Exception
     */
    public static function dateSQLToLocalized(string $sqlDate): string
    {
        $parse = explode('-', $sqlDate);
        $parse[1] = MonthFR::getLabel($parse[1]);
        return implode(' ', array_reverse($parse));
    }

    /**
     * Convertit une date FR en date SQL : 12-03-2005 >> 2005-03-12
     * Renvoie false si la date à traiter n'est pas au bon format
     * @param string $date
     * @param string $separator
     * @return bool|string
     */
    public static function dateFRToSQL(string $date, string $separator = '/'): bool|string
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
     * @param string $date
     * @param string $separator
     * @return bool|string
     */
    public static function dateSQLToFR(string $date, string $separator = '/'): bool|string
    {
        $matches = [];
        if (preg_match("#(\d{4}).(\d{2}).(\d{2})#", $date, $matches)) {
            $date = [$matches[3], $matches[2], $matches[1]];
            return implode($separator, $date);
        }

        return false;
    }

    public static function isValidCalendarDate(int $month, int $day): bool
    {
        if ($day > 0 && $day < 30) {
            return true;
        }

        if ($day === 30) {
            return $month !== 2;
        }

        if ($day === 31) {
            $monthWith31Days = [1, 3, 5, 7, 8, 10, 12];
            return in_array($month, $monthWith31Days);
        }

        return false;
    }
}
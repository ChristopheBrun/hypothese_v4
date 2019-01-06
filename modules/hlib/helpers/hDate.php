<?php

namespace app\modules\hlib\helpers;

use Carbon\Carbon;
use yii\base\Exception;


/**
 * Class dateObject
 * @package app\helpers
 *
 * @deprecated Utiliser Carbon à la place
 */
class hDate
{
    /** @var  string */
    private $day;
    /** @var  string */
    private $month;
    /** @var  string */
    private $year;

    /**
     * Construit l'objet à partir d'une date SQL (YYYY-MM-DD hh:ii:ss)
     *
     * @param string $sqlDate
     * @return $this
     * @throws Exception
     */
    public function fromSQLDate($sqlDate)
    {
        if (!preg_match('#^(\d+)\D(\d\d)\D(\d\d)(\s+)?#', $sqlDate, $matches)) {
            throw new Exception("$sqlDate n'est pas une date au format SQL");
        }

        $this->year = $matches[1];
        $this->month = $matches[2];
        $this->day = $matches[3];
        return $this;
    }

    /**
     * Transforme la saisie utilisateur pour que la date soit au format MySQL (YYYY-MM-DD)
     *
     * @param string $value La date à traiter. Format attendu : DD/MM/YYYY
     * @return string Chaine au format : YYYY/MM/DD
     * @deprecated cf BaseNews pour un exemple de traitement de ^value avec Carbon
     */
    public static function convertDateToSQLFormat($value)
    {
        $out = $value;
        if (preg_match("#^\d\d(\D)\d\d\D\d\d\d\d$#", $value, $matches)) {
            // Traite une date au format DD/MM/YYYY
            $separator = $matches[1];
            $out = implode($separator, array_reverse(explode($separator, $value)));
        }

        return $out;
    }

    /**
     * Renvoie la date de $this au format : YYYY-MM-DD
     *
     * @param string $sep
     * @return string
     */
    public function asString($sep = '-')
    {
        return $this->year . $sep . $this->month . $sep . $this->day;
    }

    /**
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param int $day
     * @return hDate
     */
    public function setDay($day)
    {
        $this->day = $day;
        return $this;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param int $month
     * @return hDate
     */
    public function setMonth($month)
    {
        $this->month = $month;
        return $this;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $year
     * @return hDate
     */
    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @param string $date
     * @param string $format
     * @return string
     */
    public static function formatLocalized($date, $format)
    {
        $carbon = Carbon::parse($date);
        if (strtotime($carbon) === false) {
            return $date;
        }

        return $carbon->formatLocalized($format);
    }
}
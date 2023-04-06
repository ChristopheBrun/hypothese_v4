<?php


namespace app\modules\ephemerides\helpers;

use Exception;
use yii\helpers\ArrayHelper;

/**
 * Class ImageStatus
 * @package app\modules\ephemerides\lib\enums
 */
class MonthFR
{
    private static array $month = [
        '01' => 'janvier',
        '02' => 'février',
        '03' => 'mars',
        '04' => 'avril',
        '05' => 'mai',
        '06' => 'juin',
        '07' => 'juillet',
        '08' => 'août',
        '09' => 'septembre',
        '10' => 'octobre',
        '11' => 'novembre',
        '12' => 'décembre',
    ];

    /**
     * @throws Exception
     */
    public static function getLabel(string $monthAsNumStr, string $default = '?'): string
    {
        return ArrayHelper::getValue(static::$month, $monthAsNumStr, $default);
    }

}
<?php


namespace app\modules\ephemerides\lib\enums;

use app\modules\ephemerides\EphemeridesModule;
use app\modules\hlib\helpers\EnumHelper;

/**
 * Class EventDateOperator
 * @package app\modules\ephemerides\enums
 */
class EventDateOperator extends EnumHelper
{
    const NO_OP = 'no';
    const SAME_DATE = 'date';
    const SAME_DAY = 'day';
    const SAME_MONTH = 'month';
    const SAME_YEAR = 'year';

    /**
     * @return array|string[]
     */
    public static function getList()
    {
        return [
            static::NO_OP => '',
            static::SAME_DATE => EphemeridesModule::t('labels', 'Date (dd-mm-yyyy)'),
            static::SAME_DAY => EphemeridesModule::t('labels', 'Calendar day (dd-mm)'),
            static::SAME_MONTH => EphemeridesModule::t('labels', 'Month (mm)'),
            static::SAME_YEAR => EphemeridesModule::t('labels', 'Year (yyyy)'),
        ];
    }

}
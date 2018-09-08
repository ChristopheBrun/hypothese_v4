<?php

namespace app\modules\hlib\lib\enums;
use app\modules\hlib\helpers\EnumHelper;
use app\modules\hlib\HLib;

/**
 * Class Month
 */
class Month extends EnumHelper
{
    const JANUARY = 1;
    const FEBRUARY = 2;
    const MARCH = 3;
    const APRIL = 4;
    const MAY = 5;
    const JUNE = 6;
    const JULY = 7;
    const AUGUST = 8;
    const SEPTEMBER = 9;
    const OCTOBER = 10;
    const NOVEMBER = 11;
    const DECEMBER = 12;

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            static::JANUARY => HLib::t('calendar', 'January'),
            static::FEBRUARY => HLib::t('calendar', 'February'),
            static::MARCH => HLib::t('calendar', 'March'),
            static::APRIL => HLib::t('calendar', 'April'),
            static::MAY => HLib::t('calendar', 'May'),
            static::JUNE => HLib::t('calendar', 'June'),
            static::JULY => HLib::t('calendar', 'July'),
            static::AUGUST => HLib::t('calendar', 'August'),
            static::SEPTEMBER => HLib::t('calendar', 'September'),
            static::OCTOBER => HLib::t('calendar', 'October'),
            static::NOVEMBER => HLib::t('calendar', 'November'),
            static::DECEMBER => HLib::t('calendar', 'December'),
        ];
    }

}
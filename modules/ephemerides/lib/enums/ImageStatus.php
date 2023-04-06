<?php


namespace app\modules\ephemerides\lib\enums;

use app\modules\hlib\helpers\EnumHelper;
use app\modules\hlib\HLib;

/**
 * Class ImageStatus
 * @package app\modules\ephemerides\lib\enums
 */
class ImageStatus extends EnumHelper
{
    const ST_ALL = 'all';
    const ST_WITH = 'with';
    const ST_WITHOUT = 'without';

    /**
     * @return array|string[]
     */
    public static function getList()
    {
        return [
            static::ST_ALL => HLib::t('labels', 'n/a'),
            static::ST_WITH => HLib::t('labels', 'With image'),
            static::ST_WITHOUT => HLib::t('labels', 'Without image'),
        ];
    }

}
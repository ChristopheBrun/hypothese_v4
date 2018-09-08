<?php

namespace app\modules\hlib\lib\enums;

use app\modules\hlib\helpers\EnumHelper;
use app\modules\hlib\HLib;

/**
 * Class Sex
 */
class Sex extends EnumHelper
{
    const MALE = 1;
    const FEMALE = 2;
    const UNKNOWN = 3;

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            static::MALE => HLib::t('labels', 'Male'),
            static::FEMALE => HLib::t('labels', 'Female'),
        ];
    }

    /**
     * @return array
     */
    public static function getExtList()
    {
        return [
            static::MALE => HLib::t('labels', 'Male'),
            static::FEMALE => HLib::t('labels', 'Female'),
            static::UNKNOWN => HLib::t('labels', 'Unknown'),
        ];
    }


    /**
     * @return array
     */
    public static function getExtKeys()
    {
        return array_keys(static::getExtList());
    }

}
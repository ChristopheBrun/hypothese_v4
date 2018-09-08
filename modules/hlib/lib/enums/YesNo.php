<?php

namespace app\modules\hlib\lib\enums;
use app\modules\hlib\helpers\EnumHelper;
use app\modules\hlib\HLib;

/**
 * Class YesNo
 */
class YesNo extends EnumHelper
{
    const NO = 0;
    const YES = 1;

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            static::YES => HLib::t('labels', 'Yes'),
            static::NO => HLib::t('labels', 'No'),
        ];
    }

}
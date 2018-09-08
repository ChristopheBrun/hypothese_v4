<?php

namespace app\modules\hlib\lib\enums;
use app\modules\hlib\helpers\EnumHelper;
use app\modules\hlib\HLib;


/**
 * Class YesNoUnknown
 */
class YesNoUnknown extends EnumHelper
{
    const NO = 0;
    const YES = 1;
    const UNKNOWN = 2;


    /**
     * @return array
     */
    public static function getList()
    {
        return [
            static::YES => HLib::t('labels', 'Yes'),
            static::NO => HLib::t('labels', 'No'),
            static::UNKNOWN => HLib::t('labels', 'Unknown'),
        ];
    }

}
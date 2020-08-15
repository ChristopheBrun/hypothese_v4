<?php


namespace app\modules\hlib\lib\enums;

use app\modules\hlib\helpers\EnumHelper;
use app\modules\hlib\HLib;

/**
 * Class ModelStatus
 * @package app\modules\hlib\lib\enums
 */
class ModelStatus extends EnumHelper
{
    const ST_ALL = 'all';
    const ST_ENABLED = 'enabled';
    const ST_DISABLED = 'disabled';

    /**
     * @return array|string[]
     */
    public static function getList()
    {
        return [
            static::ST_ALL => HLib::t('labels', 'n/a'),
            static::ST_ENABLED => HLib::t('labels', 'Enabled'),
            static::ST_DISABLED => HLib::t('labels', 'Disabled'),
        ];
    }

}
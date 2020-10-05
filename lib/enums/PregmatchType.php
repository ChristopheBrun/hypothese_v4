<?php


namespace app\lib\enums;

use app\modules\hlib\helpers\EnumHelper;

/**
 * Class PregmatchType
 * @package app\lib\enums
 */
class PregmatchType extends EnumHelper
{
    const SIMPLE = 'simple';
    const ALL = 'all';

    /**
     * @return array|string[]
     */
    public static function getList()
    {
        return [
            static::SIMPLE => 'preg_match()',
            static::ALL => 'preg_match_all()',
        ];
    }
}
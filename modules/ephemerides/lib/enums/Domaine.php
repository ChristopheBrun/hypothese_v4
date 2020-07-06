<?php


namespace app\modules\ephemerides\lib\enums;

use app\modules\hlib\helpers\EnumHelper;

/**
 * Class Domaine
 * @package app\modules\ephemerides\enums
 */
class Domaine extends EnumHelper
{
    const SCIENCES = 'sciences';
    const COMMUNISME = 'communisme';
    const MUSIQUE = 'musique';

    /**
     * @return array|string[]
     */
    public static function getList()
    {
        return [
            static::SCIENCES => "Sciences",
            static::COMMUNISME => "Communisme",
            static::MUSIQUE => "Musique",
        ];
    }

}
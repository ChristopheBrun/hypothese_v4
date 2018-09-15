<?php

namespace app\modules\user\lib\enums;

use app\modules\hlib\helpers\EnumHelper;
use app\modules\user\UserModule;


/**
 * Class AppRole
 * @package app\modules\user\enumerations
 */
class AppRole extends EnumHelper
{
    const SUPERADMIN = 'superadmin';

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            static::SUPERADMIN => UserModule::t('labels', 'Superadmin'),
        ];
    }
}
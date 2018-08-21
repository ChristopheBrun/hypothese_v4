<?php

namespace app\modules\user\lib\enums;

use app\modules\ia\helpers\EnumHelper;
use app\modules\user\UserModule;


/**
 * Class TokenType
 * @package app\modules\user\enumerations
 */
class TokenType extends EnumHelper
{
    const REGISTRATION = 1;
    const ACCESS = 2;
    const PASSWORD = 3;

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            static::ACCESS => UserModule::t('labels', 'Access'),
            static::REGISTRATION => UserModule::t('labels', 'Registration'),
            static::PASSWORD => UserModule::t('labels', 'Password'),
        ];
    }
}
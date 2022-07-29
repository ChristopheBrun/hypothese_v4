<?php

namespace app\lib\enums;

use app\modules\hlib\helpers\EnumHelper;

class DecodeFunctions extends EnumHelper
{
    const BASE64_DECODE = 'base64_decode';
    const URL_DECODE = 'urldecode';
    const RAWURL_DECODE = 'rawurldecode';

    /**
     * @return array
     */
    public static function getList(): array
    {
        return [
            static::BASE64_DECODE => static::BASE64_DECODE,
            static::URL_DECODE => static::URL_DECODE,
            static::RAWURL_DECODE => static::RAWURL_DECODE,
        ];
    }

}
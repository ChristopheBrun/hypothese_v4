<?php

namespace app\lib\enums;

use app\modules\hlib\helpers\EnumHelper;

class EncodeFunctions extends EnumHelper
{
    const BASE64_ENCODE = 'base64_encode';
    const URL_ENCODE = 'urlencode';
    const RAWURL_ENCODE = 'rawurlencode';

    /**
     * @return array
     */
    public static function getList(): array
    {
        return [
            static::BASE64_ENCODE => static::BASE64_ENCODE,
            static::URL_ENCODE => static::URL_ENCODE,
            static::RAWURL_ENCODE => static::RAWURL_ENCODE,
        ];
    }

}
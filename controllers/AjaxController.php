<?php

namespace app\controllers;

use app\lib\enums\DecodeFunctions;
use app\lib\enums\EncodeFunctions;
use yii\web\Controller;


/**
 * Class ApiController
 * @package app\controllers
 */
class AjaxController extends Controller
{
    public function actionBase64Encode(string $val): string
    {
        return base64_encode($val);
    }

    public function actionEncode(string $fct, string $val): string
    {
        switch ($fct) {
            case EncodeFunctions::BASE64_ENCODE :
                return base64_encode($val);
            case EncodeFunctions::URL_ENCODE :
                return urlencode($val);
            case EncodeFunctions::RAWURL_ENCODE :
                return rawurlencode($val);
            default :
                return "Cette fonction n'est pas traitée ici";
        }
    }

    public function actionDecode(string $fct, string $val): string
    {
        switch ($fct) {
            case DecodeFunctions::BASE64_DECODE :
                return base64_decode($val);
            case DecodeFunctions::URL_DECODE :
                return urldecode($val);
            case DecodeFunctions::RAWURL_DECODE :
                return rawurldecode($val);
            default :
                return "Cette fonction n'est pas traitée ici";
        }
    }

    public function actionBase64Decode(string $val): string
    {
        return base64_decode($val);
    }

    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////


}

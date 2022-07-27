<?php

namespace app\controllers;

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

    public function actionBase64Decode(string $val): string
    {
        return base64_decode($val);
    }

    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////


}

<?php
/**
 * Created by PhpStorm.
 * User: Christophe
 * Date: 30/06/2020
 * Time: 14:47
 */

namespace app\controllers;


use yii\web\Controller;

/**
 * Class HelperController
 * @package app\controllers
 */
class HelperController extends Controller
{

    /**
     * Traduit un path windows en path compatible linux
     * @internal fait en javacsript directement sur la page
     *
     * @return string
     */
    public function actionPathWindows()
    {
        return $this->render('path-windows');
    }
}
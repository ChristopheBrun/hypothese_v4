<?php /** @noinspection PhpUnused */

namespace app\controllers;

use yii\web\Controller;

/**
 * Class MemosController
 * @package app\controllers
 */
class MemosController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Traduit un path windows en path compatible linux
     *
     * @return string
     * @internal fait en javacsript directement sur la page
     *
     */
    public function actionConsoleWindows(): string
    {
        return $this->render('console-windows');
    }

}
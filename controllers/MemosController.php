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
     * @return string
     */
    public function actionConsoleWindows(): string
    {
        return $this->render('console-windows');
    }

    /**
     * @return string
     */
    public function actionConfigPhp(): string
    {
        return $this->render('config-php');
    }

}
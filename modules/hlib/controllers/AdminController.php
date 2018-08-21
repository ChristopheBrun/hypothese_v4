<?php

namespace app\modules\hlib\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Class AdminController
 * @package app\controllers
 */
class AdminController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Page d'accueil du site
     *
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        $command = Yii::$app->db->createCommand('select VARIABLE_VALUE from information_schema.GLOBAL_VARIABLES where VARIABLE_NAME = \'version\'');
        $mySqlVersion = $command->queryScalar();
        return $this->render('index', compact('mySqlVersion'));
    }

    /**
     * @return string
     */
    public function actionPhpinfo()
    {
        $this->layout = false;
        return $this->render('phpinfo');
    }

}

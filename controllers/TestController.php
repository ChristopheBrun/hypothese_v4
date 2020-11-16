<?php

namespace app\controllers;

use app\modules\hlib\lib\Flash;
use app\modules\user\lib\enums\AppRole;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;


/**
 * Class TestController
 * @package app\controllers
 */
class TestController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [AppRole::SUPERADMIN],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return Response|string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return Response|string
     */
    public function actionYiiError()
    {
        $msg = "Appel de Yii::error() + envoi des notifications";
        Yii::error($msg);
        Flash::error($msg);
        return $this->render('index');
    }

    /**
     * @return Response|string
     */
    public function actionSendEmail()
    {
        $to = Yii::$app->params['adminEmail'];
        $msg = "Envoi d'un mail à : $to";
        $ok = Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['fromEmail'])
            ->setTo($to)
            ->setSubject(__METHOD__)
            ->setTextBody($msg)
//            ->setHtmlBody("<b>$msg</b>")
            ->send();
        if ($ok) {
            Flash::success($msg);
        } else {
            Flash::error($msg);
        }

        return $this->render('index');
    }


    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////


}

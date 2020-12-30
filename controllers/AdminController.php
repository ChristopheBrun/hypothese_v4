<?php

namespace app\controllers;

use app\models\ConsoleCommandForm;
use app\modules\hlib\lib\Flash;
use Yii;
use yii\base\InvalidConfigException;
use Exception;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class AdminController
 * @package app\controllers
 */
class AdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['superadmin'],
                    ],
                ],
            ],

        ];
    }

    /**
     * Page d'accueil du site
     *
     * @return string
     * @throws Exception
     */
    public function actionConfig(): string
    {
        return $this->render('config', [
            'env' => YII_ENV,
        ]);
    }

    /**
     * Lance une commande console depuis la page web
     *
     * @return string
     * @throws InvalidConfigException
     */
    public function actionCommands(): string
    {
        $model = Yii::createObject(ConsoleCommandForm::class);
        $consoleOutput = '';
        if (Yii::$app->request->isPost) {
            try {
                if (!$model->load(Yii::$app->request->post())) {
                    throw new Exception('!$model->load()');
                }

                if (!$model->validate(Yii::$app->request->post())) {
                    throw new Exception('!$model->validate()');
                }

                // @see https://www.yiiframework.com/extension/yii2-console-runner
                /** @noinspection PhpUndefinedFieldInspection */
                Yii::$app->commandRunner->run($model->command, $consoleOutput);
            } catch (Exception $x) {
                Yii::error($x->getMessage());
                Flash::error("Erreur sur " . __METHOD__);
            }
        }
        return $this->render('commands', compact('model', 'consoleOutput'));
    }

    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////

}

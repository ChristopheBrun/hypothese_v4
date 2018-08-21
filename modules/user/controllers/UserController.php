<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\User;
use yii\base\ActionEvent;
use yii\base\Event;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Gestion des actions de backend sur les utilisateurs
 */
class UserController extends Controller
{
    // Création d'un compte utilisateur par un administrateur
    const EVENT_BEFORE_CREATE_USER = 'beforeCreateUser';
    const EVENT_AFTER_CREATE_USER = 'afterCreateUser';
    // Mise à jour d'un compte utilisateur par un administrateur
    const EVENT_BEFORE_UPDATE_USER = 'beforeUpdateUser';
    const EVENT_AFTER_UPDATE_USER = 'afterUpdateUser';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'actions' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true, 'roles' => ['manageUsers'],
                    ]
                ]
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $searchModel = Yii::createObject('user/UserSearch');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $model = Yii::createObject('user/User');

        Event::trigger(static::class, static::EVENT_BEFORE_CREATE_USER, new ActionEvent($this->action));
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Event::trigger(static::class, static::EVENT_AFTER_CREATE_USER, new ActionEvent($this->action, ['sender' => $model->user]));
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        Event::trigger(static::class, static::EVENT_BEFORE_UPDATE_USER, new ActionEvent($this->action, ['sender' => $model->user]));
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Event::trigger(static::class, static::EVENT_AFTER_UPDATE_USER, new ActionEvent($this->action, ['sender' => $model->user]));
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\base\InvalidConfigException
     */
    protected function findModel($id)
    {
        if (($model = Yii::createObject('user/User')->findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace app\modules\user\controllers;

use app\modules\hlib\lib\Flash;
use app\modules\user\models\form\CreateUserForm;
use app\modules\user\models\form\UpdateUserForm;
use app\modules\user\models\search\UserSearch;
use app\modules\user\UserModule;
use Exception;
use Throwable;
use Yii;
use app\modules\user\models\User;
use yii\base\ActionEvent;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

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
    public function behaviors(): array
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
     * @return string
     * @throws InvalidConfigException
     */
    public function actionIndex(): string
    {
        $searchModel = Yii::createObject(UserSearch::class);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     * @throws InvalidConfigException
     */
    public function actionCreate()
    {
        $model = Yii::createObject(CreateUserForm::class);

        if (Yii::$app->request->isPost) {
            Event::trigger(static::class, static::EVENT_BEFORE_CREATE_USER, new ActionEvent($this->action));
            try {
                $model->process(Yii::$app->request->post());
                Flash::success(UserModule::t('messages', "User created : {0}", [$model->user->email]));
                Event::trigger(static::class, static::EVENT_AFTER_CREATE_USER, new ActionEvent($this->action, ['sender' => $model->user]));
                return $this->redirect(['view', 'id' => $model->user->id]);
            } catch (Exception $x) {
                Yii::error($x->getMessage());
                Flash::error($x->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return Response|string
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id)
    {
        $model = Yii::createObject(['class' => UpdateUserForm::class, 'user' => $this->findModel($id)]);

        if (Yii::$app->request->isPost) {
            Event::trigger(static::class, static::EVENT_BEFORE_UPDATE_USER, new ActionEvent($this->action));
            try {
                $model->process(Yii::$app->request->post());
                Flash::success(UserModule::t('messages', "User updated : {0}", [$model->user->email]));
                Event::trigger(static::class, static::EVENT_AFTER_UPDATE_USER, new ActionEvent($this->action, ['sender' => $model->user]));
                return $this->redirect(['view', 'id' => $model->user->id]);
            } catch (Exception $x) {
                Yii::error($x->getMessage());
                Flash::error($x->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws Exception
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id): Response
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
     * @throws InvalidConfigException
     */
    protected function findModel(int $id): User
    {
        if (($model = Yii::createObject(User::class)->findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

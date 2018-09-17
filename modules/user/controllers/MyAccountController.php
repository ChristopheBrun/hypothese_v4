<?php

namespace app\modules\user\controllers;

use app\modules\hlib\lib\Flash;
use Exception;
use Yii;
use app\modules\user\models\User;
use app\modules\user\UserModule;
use app\modules\hlib\HLib;
use yii\base\ActionEvent;
use yii\base\Event;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MyAccountController
 */
class MyAccountController extends Controller
{
    const EVENT_BEFORE_UPDATE_USER = 'beforeUpdateUser';
    const EVENT_AFTER_UPDATE_USER = 'afterUpdateUser';

    /** @var string|array $afterEventRedirectTo Url ou route à exploiter pour une redirection après la gestion d'un événement */
    public $afterEventRedirectTo = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
//                    'delete' => ['POST'],
                ],
            ],
            'actions' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
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
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Affiche les informations du compte de l'utilisateur connecté
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = Yii::$app->user->identity;
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Mise à jour des identifiants de l'utilisateur : l'adresse mail est gérée dans ce formulaire, la mise à jour du mot de passe se fait avec un lien de ré-initialisation
     *
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionUpdateUser()
    {
        /** @var User $model */
        $model = Yii::$app->user->identity;

        if (Yii::$app->request->isPost) {
            // Traitement du formulaire
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    Event::trigger(static::class, static::EVENT_BEFORE_UPDATE_USER, new ActionEvent($this->action, ['sender' => $model]));
                    if (!$model->save()) {
                        throw new Exception('!$model->save() : ' . print_r(Yii::$app->request->post(), true));
                    }

                    $transaction->commit();
                    Event::trigger(static::class, static::EVENT_AFTER_UPDATE_USER, new ActionEvent($this->action, ['sender' => $model]));
                    Flash::success(UserModule::t('messages', "Identifiers updated"));
                    if ($this->afterEventRedirectTo !== false) {
                        return $this->redirect($this->afterEventRedirectTo);
                    }

                    return $this->redirect(['index']);
                } catch (Exception $x) {
                    Yii::error($x);
                    $transaction->rollBack();
                }
            }

            Flash::error(HLib::t('messages', 'Update error'));
        }

        return $this->render('updateUser', [
            'model' => $model,
        ]);
    }

    /**
     * Mise à jour du profil utilisateur
     * NB : un seul profil géré pour le moment
     * @todo mettre en place la gestion de plusieurs profils + option de configuration du module
     *
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionUpdateProfile()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $model = $user->profile;

        if (Yii::$app->request->isPost) {
            // Traitement du formulaire
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$model->load(Yii::$app->request->post())) {
                    throw new Exception('!$model->load() : ' . print_r(Yii::$app->request->post(), true));
                }

                if (!$model->validate()) {
                    throw new Exception('!$model->validate() : ' . print_r(Yii::$app->request->post(), true));
                }

                if (!$model->save()) {
                    throw new Exception('!$model->save() : ' . print_r(Yii::$app->request->post(), true));
                }

                $transaction->commit();
                Flash::success(UserModule::t('messages', "Profile updated"));
                return $this->redirect(['profile']);
            } catch (Exception $x) {
                Yii::error($x);
                Flash::error(HLib::t('messages', 'Update error'));
                $transaction->rollBack();
            }
        }

        return $this->render('updateProfile', [
            'model' => $model,
        ]);
    }

    /**
     * Affiche les informations du compte de l'utilisateur connecté
     *
     * @return mixed
     */
    public function actionProfile()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        return $this->render('profile', [
            'model' => $user->getProfile(),
        ]);
    }

}

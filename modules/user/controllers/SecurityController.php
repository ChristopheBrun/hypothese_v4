<?php

namespace app\modules\user\controllers;

use app\modules\hlib\HLib;
use app\modules\hlib\lib\Flash;
use app\modules\user\models\form\LoginForm;
use app\modules\user\models\form\MailRequestForm;
use app\modules\user\models\User;
use Yii;
use yii\base\ActionEvent;
use yii\base\Event;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use app\modules\user\UserModule;


/**
 * Class AuthenticationController
 * @package app\modules\user\controllers
 */
class SecurityController extends Controller
{
    const EVENT_UNKNOWN_USER = 'unknownUser';
    const EVENT_INVALID_USER = 'invalidUser';
    const EVENT_INVALID_PASSWORD = 'invalidPassword';
    const EVENT_REQUEST_NEW_PASSWORD = 'requestNewPassword';
    const EVENT_REQUEST_NEW_CONFIRMATION_LINK = 'requestNewConfirmationLink';

    /** @var string */
    public $defaultAction = 'login';

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'request-new-password', 'request-new-confirmation-link'],
                        'allow' => true, 'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true, 'roles' => ['@'],
                    ]
                ]
            ],
        ];
    }

    /**
     * Affiche & traite le formulaire de connexion
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLogin()
    {
        /** @var \app\modules\user\models\form\LoginForm $model */
        $model = Yii::createObject(LoginForm::class);

        if (Yii::$app->request->isPost) {
            // Traitement du formulaire
            if ($model->load(Yii::$app->request->post())) {
                /** @var UserModule $module */
                $module = $this->module;
                if (!$model->validate()) {
                    Flash::warning(UserModule::t('messages', "Invalid input"));
                    return $this->redirect($module->redirectAfterRegister);
                }

                /** @var \app\modules\user\models\User $user */
                $user = Yii::createObject(User::class)->find()->byEmail($model->email)->one();
                if (!$user) {
                    Event::trigger(static::class, static::EVENT_UNKNOWN_USER, new ActionEvent($this->action, ['sender' => $model]));
                    Flash::error(UserModule::t('messages', "Unkown user"));
                    return $this->redirect($module->redirectAfterRegister);
                }

                if (!$user->confirmed_at || $user->blocked_at) {
                    Event::trigger(static::class, static::EVENT_INVALID_USER, new ActionEvent($this->action, ['sender' => $model]));
                    Flash::error(UserModule::t('messages', "Invalid user"));
                    return $this->redirect($module->redirectAfterRegister);
                }

                if (!Yii::$app->security->validatePassword($model->password, $user->password_hash)) {
                    Event::trigger(static::class, static::EVENT_INVALID_PASSWORD, new ActionEvent($this->action, ['sender' => $model]));
                    Flash::error(UserModule::t('messages', "Invalid password"));
                    return $this->redirect($module->redirectAfterLogin);
                }

                if (!Yii::$app->user->login($user, $module->rememberFor)) {
                    Flash::error(UserModule::t('messages', "Login rejected"));
                    return $this->redirect($module->redirectAfterRegister);
                }

                Flash::success(UserModule::t('messages', "You are now authenticated"));
                return $this->redirect($module->redirectAfterLogin);
            }

            Flash::error(HLib::t('messages', 'Unknown error'));
        }

        // Affichage initial ou ré-affichage après erreur
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Déconnexion de l'utilisateur
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        // La session risque d'être détruite dans le logout. On sauvegarde les flashs en attente
        Flash::savePendingFlashes();

        if (!Yii::$app->user->logout()) {
            Flash::error(UserModule::t('messages', "Error on logout"));
        }

        Flash::reloadPendingFlashes();
        Flash::success(UserModule::t('messages', "Logout successful"));
        /** @noinspection PhpUndefinedFieldInspection */
        $redirectTo = $this->module->redirectAfterLogout ? Url::to($this->module->redirectAfterLogout) : Yii::$app->request->getReferrer();
        return $this->redirect($redirectTo);
    }

    /**
     * Demande de ré-initialisation du mot de passe
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRequestNewPassword()
    {
        /** @var MailRequestForm $model */
        $model = Yii::createObject(MailRequestForm::class);

        if (Yii::$app->request->isPost) {
            // Traitement du formulaire
            if ($model->load(Yii::$app->request->post())) {
                // Envoi d'un mail avec le lien (action par défaut)
                // @see \app\modules\user\lib\UserEventHandler
                Event::trigger(static::class, static::EVENT_REQUEST_NEW_PASSWORD, new ActionEvent($this->action, ['sender' => $model]));
                return $this->redirect(Yii::$app->request->getReferrer());
            }

            Flash::error(HLib::t('messages', "There are errors in your form"));
        }

        return $this->render('requestNewPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Gestion du formulaire permettant de demander l'envoi d'un nouveau lien de confirmation
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRequestNewConfirmationLink()
    {
        /** @var MailRequestForm $model */
        $model = Yii::createObject(MailRequestForm::class);

        if (Yii::$app->request->isPost) {
            // Traitement du formulaire
            if ($model->load(Yii::$app->request->post())) {
                /** @var User $user */
                $user = Yii::createObject(User::class)->find()->byEmail($model->email)->one();
                if (!$user) {
                    // RAF
                } elseif ($user->confirmed_at) {
                    // Compte déjà confirmé, inutile de renvoyer un lien
                    Flash::warning(UserModule::t('messages', "This account is already confirmed"));
                } else {
                    // Envoi du lien
                    Event::trigger(static::class, static::EVENT_REQUEST_NEW_CONFIRMATION_LINK, new ActionEvent($this->action, ['sender' => $model]));
                }

                return $this->redirect(Yii::$app->request->getReferrer());
            }

            Flash::error(HLib::t('messages', "There are errors in your form"));
        }

        // Affichage initial ou ré-affichage après erreur
        return $this->render('requestNewConfirmationLink', [
            'model' => $model,
        ]);
    }

}
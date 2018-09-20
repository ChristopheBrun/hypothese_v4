<?php

namespace app\modules\user\controllers;

use app\modules\hlib\HLib;
use app\modules\user\lib\enums\TokenType;
use app\modules\user\models\form\PasswordForm;
use app\modules\user\models\Token;
use app\modules\user\models\User;
use app\modules\user\UserModule;
use app\modules\hlib\lib\Flash;
use app\modules\user\filters\EnableRegistration;
use app\modules\user\models\form\RegistrationForm;
use Exception;
use Yii;
use yii\base\ActionEvent;
use yii\base\Event;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * RegistrationController is responsible for all registration process, which includes registration of a new account,
 * resending confirmation tokens, email confirmation and registration via social networks.
 *
 * @property \app\modules\user\UserModule $module
 */
class RegistrationController extends Controller
{
    const EVENT_BEFORE_REGISTER = 'beforeRegister';
    const EVENT_AFTER_REGISTER = 'afterRegister';

    const EVENT_BEFORE_CONFIRM_PASSWORD = 'beforeConfirmPassword';
    const EVENT_AFTER_CONFIRM_PASSWORD = 'afterConfirmPassword';

    const EVENT_REQUEST_NEW_PASSWORD = 'requestNewPassword';

    const EVENT_BEFORE_RESET_PASSWORD = 'beforeResetPassword';
    const EVENT_AFTER_RESET_PASSWORD = 'afterResetPassword';

    /** @var string */
    public $defaultAction = 'register';

    /** @inheritdoc */
    public function behaviors()
    {
        /** @noinspection PhpUndefinedFieldInspection */
        $enableRegistration = $this->module->enableRegistration;
        return [
            [
                'class' => EnableRegistration::class,
                'enable' => $enableRegistration,
            ],
            [
                'class' => AccessControl::class,
                'rules' => [
                    // actions réservées aux utilisateurs non authentifiés
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ],
                ]
            ],
        ];
    }

    /**
     * Gestion du formulaire d'inscription
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionRegister()
    {
        /** @var RegistrationForm $model */
        $model = Yii::createObject('user/RegistrationForm');

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                Event::trigger(static::class, static::EVENT_BEFORE_REGISTER, new ActionEvent($this->action));

                // Inscription de l'utilisateur
                if ($model->registerUser()) {
                    // Action par défaut : envoi d'un mail avec le lien de création du mot de passe
                    // @see \app\modules\user\lib\UserEventHandler
                    Event::trigger(static::class, static::EVENT_AFTER_REGISTER, new ActionEvent($this->action, ['sender' => $model->user]));
                    return $this->redirect($this->module->redirectAfterRegister);
                }
            }

            Flash::error(HLib::t('messages', "There are errors in your form"));
        }

        // Affichage initial ou ré-affichage après arreur
        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * Gestion du formulaire de confirmation du mot de passe
     *
     * @param int $id
     * @param string $code
     * @return string
     * @throws Yii\web\HttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \Throwable
     */
    public function actionConfirmPassword($id, $code)
    {
        if (!($user = Yii::createObject('user/User')->findOne(['id' => $id]))) {
            throw new NotFoundHttpException();
        }

        $token = Token::findToken(TokenType::REGISTRATION, $user->id, $code, $this->module->rememberConfirmationTokenFor);
        if (!$token) {
            Flash::error(UserModule::t('messages', 'The link is invalid or expired. Please try requesting a new one'));
            return $this->redirect('/');
        }

        /** @var PasswordForm $model */
        $model = Yii::createObject('user/PasswordForm');
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                Event::trigger(static::class, static::EVENT_BEFORE_CONFIRM_PASSWORD, new ActionEvent($this->action));

                // Mise à jour du mot de passe
                if ($this->processPasswordForm($model, $user, $token)) {
                    Event::trigger(static::class, static::EVENT_AFTER_CONFIRM_PASSWORD, new ActionEvent($this->action, ['sender' => $user]));
                    Flash::success(UserModule::t('messages', "Your password has been successfully created. Your account is now validated"));

                    // Mot de passe mis à jour. On connecte l'utilisateur à con compte avant de rediriger
                    Yii::$app->user->login($user, $this->module->rememberFor);
                    return $this->redirect($this->module->redirectAfterConfirmPassword);
                }
            }

            Flash::error(HLib::t('messages', "There are errors in your form"));
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Ré-initialisation du mot de passe
     *
     * @param int $id
     * @param string $code
     * @return string
     * @throws Yii\web\HttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \Throwable
     */
    public function actionResetPassword($id, $code)
    {
        if (!($user = Yii::createObject('user/User')->findOne(['id' => $id]))) {
            throw new NotFoundHttpException();
        }

        $token = Token::findToken(TokenType::PASSWORD, $user->id, $code, $this->module->rememberPasswordTokenFor);
        if (!$token) {
            Flash::error(UserModule::t('messages', 'The link is invalid or expired. Please try requesting a new one'));
            return $this->redirect('/');
        }

        /** @var PasswordForm $model */
        $model = Yii::createObject('user/PasswordForm');
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                Event::trigger(static::class, static::EVENT_BEFORE_RESET_PASSWORD, new ActionEvent($this->action));

                // Mise à jour du mot de passe
                if ($this->processPasswordForm($model, $user, $token)) {
                    Event::trigger(static::class, static::EVENT_AFTER_RESET_PASSWORD, new ActionEvent($this->action, ['sender' => $user]));
                    Flash::success(UserModule::t('messages', "Your password has been successfully updated"));

                    // Mot de passe mis à jour? On connecte l'utilisateur à son compte avant de rediriger
                    Yii::$app->user->login($user, $this->module->rememberFor);
                    return $this->redirect([$this->module->redirectAfterResetPassword]);
                }
            }

            Flash::error(HLib::t('messages', "There are errors in your form"));
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Traitement du formulaire de mise à jour du mot de passe : mise à jour, suppression du jeton
     * @internal Le formulaire doit avoir été validé en amont
     *
     * @param PasswordForm $model
     * @param User $user
     * @param Token $token
     * @return bool
     * @throws \Throwable
     */
    protected function processPasswordForm(PasswordForm $model, User $user, Token $token)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$model->resetPassword($user)) {
                throw new Exception('!$model->resetPassword()');
            }

            if ($token->delete() === false) {
                throw new Exception('$token->delete() === false');
            }

            $transaction->commit();
            return true;
        } catch (Exception $x) {
            Yii::error($x);
            $transaction->rollBack();
        }

        return false;
    }

}

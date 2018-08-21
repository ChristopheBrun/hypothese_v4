<?php

namespace app\modules\user\lib;

use app\modules\ia\lib\Flash;
use app\modules\user\controllers\MyAccountController;
use app\modules\user\controllers\RegistrationController;
use app\modules\user\controllers\UserController;
use app\modules\user\lib\enums\TokenType;
use app\modules\user\models\form\MailRequestForm;
use app\modules\user\models\Token;
use app\modules\user\models\User;
use Yii;
use app\modules\user\UserModule;
use app\modules\ia\IAModule as IA;
use yii\base\ActionEvent;
use yii\base\Component;
use yii\base\Event;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\UserEvent;


/**
 * Class UserEventHandler
 * @package app\lib
 *
 * Gestion des événements associés aux utilisateurs
 */
class UserEventHandler extends Component
{
    /**
     * Stockage des utilisateurs récupérés dans onBeforeUpdateUser()
     * @var array id => User
     */
    protected $userDirtyAttributes = [];

    /** @var  UserEventHandler */
    protected static $singleton;

    /**
     * Singleton à appeler au lancement de l'application ou du module pour qu'on puisse s'abonner aux événements qui nous intéressent
     * @see \app\components\Application::init()
     *
     * @return UserEventHandler
     * @throws NotSupportedException
     */
    public static function singleton()
    {
        if (!static::$singleton) {
            static::$singleton = new static();
        }

        return static::$singleton;
    }

    /**
     * UserEventHandler constructor.
     *
     * @param array $config
     * @throws NotSupportedException
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        if (!static::$singleton) {
            static::$singleton = $this;
            $this->subscribeEvents();
        } else {
            throw new NotSupportedException(IA::t('messages', "Only one instance allowed"));
        }
    }

    /**
     * Abonnement aux événements à traiter
     */
    protected function subscribeEvents()
    {
        Event::on(RegistrationController::class, RegistrationController::EVENT_AFTER_REGISTER, [$this, 'onAfterRegister']);
        Event::on(RegistrationController::class, RegistrationController::EVENT_REQUEST_NEW_PASSWORD, [$this, 'onRequestNewPassword']);
        Event::on(RegistrationController::class, RegistrationController::EVENT_REQUEST_NEW_CONFIRMATION_LINK, [$this, 'onRequestNewConfirmationLink']);

        Event::on(UserController::class, UserController::EVENT_AFTER_CREATE_USER, [$this, 'onAfterCreateUser']);
        Event::on(UserController::class, UserController::EVENT_BEFORE_UPDATE_USER, [$this, 'onBeforeUpdateUser']);
        Event::on(UserController::class, UserController::EVENT_AFTER_UPDATE_USER, [$this, 'onAfterUpdateUser']);
        Event::on(MyAccountController::class, MyAccountController::EVENT_BEFORE_UPDATE_USER, [$this, 'onBeforeUpdateOwnUser']);
        Event::on(MyAccountController::class, MyAccountController::EVENT_AFTER_UPDATE_USER, [$this, 'onAfterUpdateOwnUser']);

        if (is_a(Yii::$app, 'yii\web\Application')) {
            Event::on(yii\web\User::class, yii\web\User::EVENT_AFTER_LOGIN, [$this, 'onAfterLogin']);
        }
    }

    /**
     * @return UserMail
     * @throws \yii\base\InvalidConfigException
     */
    protected function getMailer()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Yii::createObject('user/UserMail');
    }

    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////

    /**
     * Actions à effectuer après l'inscription d'un utilisateur
     *  - envoi d'un mail avec le lien pour confirmer son mot de passe
     *  - affichage d'un message flash
     *
     * @param ActionEvent $event
     * @throws \yii\base\InvalidConfigException
     */
    public function onAfterRegister(ActionEvent $event)
    {
        /** @var User $user */
        $user = $event->sender;

        $to = $user->email;
        $token = Token::generateTokenForUser($user->id, TokenType::REGISTRATION);
        $url = Url::to(['/user/registration/confirm-password', 'code' => $token->code, 'id' => $user->id], true);

        $this->getMailer()->passwordConfirmationLink($to, $user, $token, $url);
        Flash::success(UserModule::t('messages',
            "You are now registered on our site. We are sending you a mail with the instructions to activate your account"));
    }

    /**
     * Actions à effectuer après la demande d'un nouveau lien de confirmation
     *  - renvoi dun mail de confirmation
     *  - affichage d'un message flash
     *
     * @param ActionEvent $event
     * @throws \yii\base\InvalidConfigException
     */
    public function onRequestNewConfirmationLink(ActionEvent $event)
    {
        /** @var MailRequestForm $model */
        $model = $event->sender;

        $to = $model->email;
        $user = Yii::createObject('user/User')->find()->byEmail($to)->one();
        if ($user) {
            $token = Token::generateTokenForUser($user->id, TokenType::REGISTRATION);
            $url = Url::to(['/user/registration/confirm-password', 'code' => $token->code, 'id' => $user->id], true);

            $this->getMailer()->passwordConfirmationLink($to, $user, $token, $url);
            Flash::success(UserModule::t('messages',
                "We are sending you a new mail with the instructions to activate your account"));
        }
    }

    /**
     * Actions à effectuer après la demande d'un nouveau lien pour un mot de passe
     *  - envoi d'un mail avec le lien pour confirmer son mot de passe
     *  - affichage d'un message flash
     *
     * @param ActionEvent $event
     * @throws \yii\base\InvalidConfigException
     */
    public function onRequestNewPassword(ActionEvent $event)
    {
        /** @var MailRequestForm $model */
        $model = $event->sender;

        $to = $model->email;
        $user = Yii::createObject('user/User')->find()->byEmail($to)->one();
        if ($user) {
            $token = Token::generateTokenForUser($user->id, TokenType::PASSWORD);
            $url = Url::to(['/user/registration/reset-password', 'code' => $token->code, 'id' => $user->id], true);

            $this->getMailer()->passwordResetLink($to, $user, $token, $url);
            Flash::success(UserModule::t('messages', "We are sending you a mail with the instructions to reset your password"));
        }
    }

    /**
     * Actions à effectuer après la connexion d'un utilisateur
     *
     * @param UserEvent $event
     */
    public function onAfterLogin(UserEvent $event)
    {
        /** @var User $user */
        $user = $event->identity;
        $user->logged_in_from = Yii::$app->request->getUserIP();
        $user->logged_in_at = date('Y-m-d H:i:s');
        ++$user->password_usage;
        if (!$user->save()) {
            Yii::error('!$user->identity->save()');
        }
    }

    /**
     * Actions à effectuer après la création d'un compte utilisateur par un admin
     *  - envoi d'un mail avec le lien pour confirmer son mot de passe
     *
     * @param ActionEvent $event
     * @throws \yii\base\InvalidConfigException
     */
    public function onAfterCreateUser(ActionEvent $event)
    {
        /** @var User $user */
        $user = $event->sender;

        $to = $user->email;
        $token = Token::generateTokenForUser($user->id, TokenType::REGISTRATION);
        $url = Url::to(['/user/registration/confirm-password', 'code' => $token->code, 'id' => $user->id], true);
        $this->getMailer()->userAccountCreation($to, $user, $token, $url);
    }

    /**
     * Actions à effectuer avant la mise à jour en backend d'un compte utilisateur
     *  - on mémorise l'utilisateur qui va être modifié
     *
     * @param ActionEvent $event
     */
    public function onBeforeUpdateUser(ActionEvent $event)
    {
        /** @var User $user */
        $user = $event->sender;
        $this->userDirtyAttributes[$user->id] = $user->getDirtyAttributes();
    }

    /**
     * Actions à effectuer après la mise à jour en backend d'un compte utilisateur
     *  - selon la configuration du module, reset possible du mot de passe
     *
     * @param ActionEvent $event
     * @throws \yii\base\InvalidConfigException
     */
    public function onAfterUpdateUser(ActionEvent $event)
    {
        /** @var User $user */
        $user = $event->sender;

        $dirtyAttributes = ArrayHelper::getValue($this->userDirtyAttributes, $user->id, []);
        if (array_key_exists('email', $dirtyAttributes) && UserModule::getInstance()->resetPasswordAfterEmailChange) {
            // Si le mail a changé, il faut ré-initialiser le mot de passe
            $to = $user->email;
            $token = Token::generateTokenForUser($user->id, TokenType::PASSWORD);
            $url = Url::to(['/user/registration/reset-password', 'code' => $token->code, 'id' => $user->id], true);

            $user->password_hash = '';
            $user->save();
            $this->getMailer()->accountUpdatedByAdmin($to, $user, $token, $url);
        }
    }

    /**
     * Actions à effectuer avant la mise à jour de son propre compte utilisateur
     *  - on mémorise l'utilisateur qui va être modifié
     *
     * @param ActionEvent $event
     */
    public function onBeforeUpdateOwnUser(ActionEvent $event)
    {
        /** @var User $user */
        $user = $event->sender;
        $this->userDirtyAttributes[$user->id] = $user->getDirtyAttributes();
    }

    /**
     * Actions à effectuer après la mise à jour de son propre compte utilisateur
     *  - selon la configuration du module, reset possible du mot de passe
     *
     * @param ActionEvent $event
     * @throws \yii\base\InvalidConfigException
     */
    public function onAfterUpdateOwnUser(ActionEvent $event)
    {
        /** @var User $user */
        $user = $event->sender;

        $dirtyAttributes = ArrayHelper::getValue($this->userDirtyAttributes, $user->id, []);
        if (array_key_exists('email', $dirtyAttributes) && UserModule::getInstance()->resetPasswordAfterEmailChange) {
            // Si le mail a changé, il faut ré-initialiser le mot de passe
            $to = $user->email;
            $token = Token::generateTokenForUser($user->id, TokenType::PASSWORD);
            $url = Url::to(['/user/registration/reset-password', 'code' => $token->code, 'id' => $user->id], true);

            $user->password_hash = '';
            $user->save();
            /** @var MyAccountController */
            $controller = $event->action->controller;
            $controller->afterEventRedirectTo = ['/user/security/logout'];
            Flash::success(UserModule::t('messages', "You have to reset your password. Please check your mails for further instructions"));
            $this->getMailer()->accountUpdatedByOwner($to, $user, $token, $url);
        }
    }
}
<?php

namespace app\modules\user;

use app\modules\user\lib\UserEventHandler;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;
use yii\console\Application;

/**
 * user module definition class
 */
class UserModule extends Module implements BootstrapInterface
{
    //
    // Configuration
    //

    /** @var int */
    public $password_minLength = 6;

    /** @var string Route de redirection après avoir créé son mot de passe */
    public $password_redirectAfterConfirm = '/';

    /** @var string Route de redirection après avoir changé son mot de passe */
    public $password_redirectAfterReset = '/';

    /** @var int Durée de validité d'un jeton de reset du mot de passe, en secondes */
    public $password_rememberResetTokenFor = 3600; // 60*60 secondes = 1h

    /** @var bool Indique s'il faut obliger l'utilisateur à renouveler son mot de passe après qu'il a modifié son email */
    public $password_resetAfterEmailChange = false;

    /** @var bool $registration_confirmationRequired true => on passe par un mail de confirmation avant d'activer le compte sur le site */
    public $registration_confirmationRequired = false;

    /** @var bool Active ou désactive les page d'inscription */
    public $registration_enable = true;

    /** @var string Route de redirection après authentification */
    public $redirectAfterLogin = '/';

    /** @var string Route de redirection après déconnexion */
    public $redirectAfterLogout;

    /** @var string Route de redirection après inscription */
    public $redirectAfterRegister = '/';

    /** @var int Durée de validité d'un jeton de confirmation, en secondes */
    public $rememberConfirmationTokenFor = 3600; // 60*60 secondes = 1h

    /** @var int Durée d'une session, en secondes */
    public $rememberFor = 86400; // 60*60*24 secondes = 1j

    //
    //
    //

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        static::registerTranslations();
        // Lancement du gestionnaire d'événements (singleton)
        Yii::createObject(UserEventHandler::class);
    }

    /**
     * @param $app
     */
    public function bootstrap($app)
    {
        // Il faut rendre les commandes du module accessibles depuis la console
        if ($app instanceof Application) {
            $this->controllerNamespace = 'app\modules\user\commands';
        }
    }

    /**
     * Déclaration des ressources pour les chaines et leur traduction
     */
    public static function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/user/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/modules/user/messages',
            'fileMap' => [
                'modules/user/labels' => 'app.php',
                'modules/user/messages' => 'messages.php',
            ],
        ];
    }

    /**
     * Raccourci pour la fonction de traduction
     *
     * @param string $category
     * @param string $message
     * @param array $params
     * @param string $language
     * @return mixed
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/user/' . $category, $message, $params, $language);
    }

}

<?php

namespace app\modules\user;

use app\modules\hlib\helpers\h;
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

    /** @var int Durée de validité d'un jeton de confirmation, en secondes */
    public int $confirmationToken_rememberFor = 3600; // 60*60 secondes = 1h

    /** @var int */
    public int $password_minLength = 6;

    /** @var string Route de redirection après avoir créé son mot de passe */
    public string $password_redirectAfterConfirm = '/';

    /** @var string Route de redirection après avoir changé son mot de passe */
    public string $password_redirectAfterReset = '/';

    /** @var int Durée de validité d'un jeton de reset du mot de passe, en secondes */
    public int $password_rememberResetTokenFor = 3600; // 60*60 secondes = 1h

    /** @var bool Indique s'il faut obliger l'utilisateur à renouveler son mot de passe après qu'il a modifié son email */
    public bool $password_resetAfterEmailChange = false;

    /** @var string Route de redirection après authentification */
    public string $redirectAfterLogin = '/';

    /** @var string Route de redirection après déconnexion */
    public string $redirectAfterLogout = '/';

    /** @var string Route de redirection après inscription */
    public string $redirectAfterRegister = '/';

    /** @var bool $registration_confirmationRequired true => on passe par un mail de confirmation avant d'activer le compte sur le site */
    public bool $registration_confirmationRequired = false;

    /** @var bool Active ou désactive les page d'inscription */
    public bool $registration_enable = true;

    /** @var int Durée d'une session, en secondes */
    public int $session_rememberFor = 86400; // 60*60*24 secondes = 1j

    //
    //
    //

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        static::registerTranslations();

        // initialisation des gestionnaires d'événements
        /** @var UserEventHandler $class */
        $class = h::getClass(UserEventHandler::class);
        $class::singleton();
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
                'modules/user/labels' => 'labels.php',
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
     * @param string|null $language
     * @return mixed
     */
    public static function t(string $category, string $message, array $params = [], string $language = null)
    {
        return Yii::t('modules/user/' . $category, $message, $params, $language);
    }

}

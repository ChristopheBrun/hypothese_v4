<?php

namespace app\modules\user;

use app\modules\user\lib\UserMail;
use app\modules\user\lib\UserEventHandler;
use app\modules\user\models\AuthItem;
use app\modules\user\models\form\LoginForm;
use app\modules\user\models\form\MailRequestForm;
use app\modules\user\models\form\PasswordForm;
use app\modules\user\models\form\RegistrationForm;
use app\modules\user\models\Profile;
use app\modules\user\models\search\UserSearch;
use app\modules\user\models\User;
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

    /** @var bool Active ou désactive les page d'inscription */
    public $enableRegistration = true;

    /** @var string Route de redirection après inscription */
    public $redirectAfterRegister = '/';

    /** @var string Route de redirection après authentification */
    public $redirectAfterLogin = '/';

    /** @var string Route de redirection après avoir créé son mot de passe */
    public $redirectAfterConfirmPassword = '/';

    /** @var string Route de redirection après avoir changé son mot de passe */
    public $redirectAfterResetPassword = '/';

    /** @var string Route de redirection après déconnexion */
    public $redirectAfterLogout;

    /** @var bool Indique s'il faut obliger l'utilisateur à renouveler son mot de passe après qu'il a modifié son email */
    public $resetPasswordAfterEmailChange = false;

    /** @var int Durée de validité d'un jeton de confirmation, en secondes */
    public $rememberConfirmationTokenFor = 3600; // 60*60 secondes = 1h

    /** @var int Durée de validité d'un jeton de reset du mot de passe, en secondes */
    public $rememberPasswordTokenFor = 3600; // 60*60 secondes = 1h

    /** @var int Durée d'une session, en secondes */
    public $rememberFor = 86400; // 60*60*24 secondes = 1j

    /**
     * @var array Mapping des classes créées dans le module
     * @see Yii::createObject()
     */
    public $classMap = [];

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

        $this->registerClassDefinitions();
        static::registerTranslations();

        // Lancement du gestionnaire d'événements (singleton)
        Yii::createObject('user/UserEventHandler');
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
     * @param string $language
     * @return mixed
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/user/' . $category, $message, $params, $language);
    }

    /**
     * Fusion les définitions de classes issues de la configuration avec les définitions fournies par défaut
     * Enregistrement des définitions dans le DI de l'application
     */
    protected function registerClassDefinitions()
    {
        $defaultClassMap = [
            //
            'user/AuthItem' => AuthItem::class,
            'user/LoginForm' => LoginForm::class,
            'user/MailRequestForm' => MailRequestForm::class,
            'user/PasswordForm' => PasswordForm::class,
            'user/Profile' => Profile::class,
            'user/RegistrationForm' => RegistrationForm::class,
            'user/User' => User::class,
            'user/UserSearch' => UserSearch::class,
            //
            'user/UserEventHandler' => UserEventHandler::class,
            'user/UserMail' => UserMail::class,
        ];

        foreach ($defaultClassMap as $key => $classDefinition) {
            if (!array_key_exists($key, $this->classMap)) {
                $this->classMap[$key] = $classDefinition;
            }
        }

        Yii::$container->setDefinitions($this->classMap);
    }

}

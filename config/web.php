<?php

use yii\log\EmailTarget;
use yii\log\FileTarget;

switch (YII_ENV) {
    case 'dev':
        $db = require_once __DIR__ . '/private/db.dev.php';
        $pwd = require_once __DIR__ . '/private/pwd.dev.php';
        break;
    case 'prod':
        $db = require_once __DIR__ . '/private/db.prod.php';
        $pwd = require_once __DIR__ . '/private/pwd.prod.php';
        break;
    default :
        /** @noinspection PhpUnhandledExceptionInspection */
        throw new Exception("Environnement non défini : " . YII_ENV);
}

/**
 * On expose ici la configuration de production qui sera sauvegardée sous Git.
 * La configuration peut être surchargée dans les fichiers config.xxx.php et private/config.xxx.php
 *
 * Ce fichier  est archivé sous Git : NE JAMAIS PUBLIER ICI DES INFORMTIONS CONFIDENTIELLES.
 *
 * Les fichiers du sous-répertoire private/ ne sont pas inclus dans Git : c'est là que doivent se trouver les données confidentielles
 * qu'il ne faut pas publier hors de nos serveurs (dont les mots de passe).
 *
 * Les fichiers et règlages personnels des développeurs doivent aussi être mis dans private/ pour ne pas encombrer le dépôt.
 */

$config = [
    //----------------------------------------------
    // Attributs
    //----------------------------------------------
    'id' => 'hypothese',
    'name' => 'Hypothese.net',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'hlib',
        'user', //le composant web/user
        function () {
            return Yii::$app->getModule('user');
        }, // le module user
        'ephemerides',
    ],
    'on beforeRequest' => function () {
        if (!Yii::$app->request->isSecureConnection) {
            $url = Yii::$app->request->getAbsoluteUrl();
            $url = str_replace('http:', 'https:', $url);
            Yii::$app->getResponse()->redirect($url);
            Yii::$app->end();
        }
    },
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@webroot' => '/www',
    ],
    'container' => [
        'definitions' => require(__DIR__ . '/classMap.php'),
    ],
    'language' => 'fr-FR',
    'version' => '4.0.02',

    //----------------------------------------------
    // Paramètres
    //----------------------------------------------
    'params' => [
        'adminEmail' => 'superadmin@hypothese.net',
        'fromEmail' => 'no-reply@hypothese.net',
        'images' => [
            'driver' => 'gd',
            'webDirectory' => 'images',
        ],
    ],

    //----------------------------------------------
    // Composants
    //----------------------------------------------
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
//        'cache' => Yii::$app->cache,
//        'cacheKey' => '654fgrgerg',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'commandRunner' => [
            'class' => 'toriphes\console\Runner',
        ],
        'db' => $db,
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                'labels' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
                'messages' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => EmailTarget::class,
                    'levels' => ['error'],
                    'message' => [
                        'from' => 'supervision@hypothese.net',
                        'to' => 'superadmin@hypothese.net',
                        'subject' => "Erreur bloquante",
                    ],
                    'except' => [
                        'yii\web\HttpException:405',
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:403',
                        'yii\web\HttpException:400',
                        'yii\debug\Module::checkAccess',
                    ],
                ]
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.phpnet.org',
                'username' => 'postmaster@hypothese.net',
                'password' => $pwd['mailer.transport'],
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'dMWHB4YSQxcVE5TKKEFL0BrURcgMBqrn',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'helpers/<action:[\w-]+>' => 'utilitaires/<action>',
            ],
        ],
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'authTimeout' => 14400, // 4 * 60 * 60 = 4 heures
            'enableAutoLogin' => 'true',
            'loginUrl' => '/login',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/modules/user/views' => '@app/views/user',
                ],
            ],
        ],
    ],

    //----------------------------------------------
    // Modules
    //----------------------------------------------
    'modules' => [
        'ephemerides' => [
            'class' => 'app\modules\ephemerides\EphemeridesModule',
        ],
        'hlib' => [
            'class' => 'app\modules\hlib\HLib',
        ],
        'user' => [
            'class' => 'app\modules\user\UserModule',
            'confirmationToken_rememberFor' => 259200, // 72h = 72 * 3600 secondes
            'password_resetAfterEmailChange' => false,
        ],
    ],
];

// Surcharge de la configuration avec les autres données condidentielles ou privées
switch (YII_ENV) {
    case 'prod' :
        require_once __DIR__ . '/private/config.prod.php';
        break;
    default :
        require_once __DIR__ . '/private/config.dev.php';
}

// La barre de debug
if (YII_DEBUG) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '88.187.229.52'],
    ];
}

return $config;

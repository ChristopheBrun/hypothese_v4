<?php

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
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'container' => [
        'definitions' => require(__DIR__ . '/classMap.php'),
    ],
    'language' => 'fr-FR',
    'version' => '4.0.1',

    //----------------------------------------------
    // Paramètres
    //----------------------------------------------
    'params' => [
        'adminEmail' => 'superadmin@hypothese.net',
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
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
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
//                'test' => 'site/test',
            ],
        ],
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'authTimeout' => 14400, // 4 * 60 * 60 = 4 heures
            'enableAutoLogin' => 'true',
            'loginUrl' => '/login',
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

// Surcharge de la configuration selon l'environnement
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

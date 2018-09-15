<?php

$params = require __DIR__ . '/params.php';
switch (YII_ENV) {
    case 'prod':
        $db = require_once __DIR__ . '/private/db.prod.php';
        break;
    default:
        $db = require_once __DIR__ . '/private/db.dev.php';
}

$config = [
    'id' => 'basic',
    'name' => 'Hypothese.net',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'language' => 'fr-FR',
    'params' => $params,
    //
    'components' => [
        'db' => isset($db) ? $db : [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=hypothese_v4',
            'username' => 'root',
            'password' => 'tagada',
            'charset' => 'utf8',
        ],
        //
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
//        'cache' => Yii::$app->cache,
//        'cacheKey' => '654fgrgerg',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
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
            'useFileTransport' => YII_ENV == 'dev' ? true : false,
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'dMWHB4YSQxcVE5TKKEFL0BrURcgMBqrn',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'authTimeout' => 14400, // 4 * 60 * 60 = 4 heures
            'enableAutoLogin' => 'true',
            'loginUrl' => '/login',
        ],
    ],
    //
    'modules' => [
        'user' => [
            'class' => 'app\modules\user\UserModule',
            //
            'rememberConfirmationTokenFor' => 259200, // 72h = 72 * 3600 secondes
            'resetPasswordAfterEmailChange' => false,
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

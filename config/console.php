<?php

switch (YII_ENV) {
    case 'prod':
        $db = require_once __DIR__ . '/private/db.prod.php';
        break;
    default:
        $db = require_once __DIR__ . '/private/db.dev.php';
}

$config = [
    //----------------------------------------------
    // Attributs
    //----------------------------------------------
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'hlib', function () {
        return Yii::$app->getModule('user');
    }],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
//    'controllerMap' => [
//        'fixture' => [ // Fixture generation command line.
//            'class' => 'yii\faker\FixtureController',
//        ],
//    ],

    //----------------------------------------------
    // Paramètres
    //----------------------------------------------
    'params' => [
        'adminEmail' => 'admin@example.com',
    ],

    //----------------------------------------------
    // Composants
    //----------------------------------------------
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
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],

    //----------------------------------------------
    // Modules
    //----------------------------------------------
    'modules' => [
        'hlib' => [
            'class' => 'app\modules\hlib\HLib',
        ],
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
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;

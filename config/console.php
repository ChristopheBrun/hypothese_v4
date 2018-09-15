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
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
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
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
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
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;

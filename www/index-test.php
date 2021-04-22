<?php

// NOTE: Make sure this file is not accessible when deployed to production
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}

define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', false);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

// Il faut faire le require explicitement pour nos classes Application...
// @todo_cbn ce n'est pas le cas quand on initialise yii\base\Application. Voir ce qui manque pour activer l'autoload correctement
require_once(__DIR__ . '/../modules/hlib/components/Application.php');
require_once(__DIR__ . '/../components/Application.php');
/** @noinspection PhpUnhandledExceptionInspection */
$application = new app\components\Application($config);
$application->run();

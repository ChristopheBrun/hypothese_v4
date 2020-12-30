<?php

/**
 * Point d'entrée de l'application
 * 1 - Chargement de l'autoloader.
 * 2 - Initialisation de l'application et lancement
 */

require_once __DIR__ . '/../config/web.env.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

// Il faut faire le require explicitement pour nos clases Application...
// @todo_cbn ce n'est pas le cas quand on initialise yii\base\Application. Voir ce qui manque pour activer l'autoload correctement
require_once(__DIR__ . '/../modules/hlib/components/Application.php');
require_once(__DIR__ . '/../components/Application.php');
/** @noinspection PhpUnhandledExceptionInspection */
(new app\components\Application($config))->run();

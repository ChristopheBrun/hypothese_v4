<?php

/**
 * Point d'entrée de l'application
 * 1 - Chargement de l'autoloader.
 *      NB : celui-ci n'est pas encore actif quand on crée l'instance de l'application
 * 2 - Initialisation de l'application et lancement
 *      NB : on initialise notre propre variante de la classe Application
 */

require_once __DIR__ . '/../config/web.env.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';
require_once(__DIR__ . '/../components/Application.php');
/** @noinspection PhpUnhandledExceptionInspection */
(new app\components\Application($config))->run();

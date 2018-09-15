<?php

$environments = [
    'hypothese_v4' => 'dev',
    'www.hypothese.net' => 'prod',
];

$env = '';
if (array_key_exists("HTTP_HOST", $_SERVER)) {
    // NOTE : uniquement en CGI car en CLI, $_SERVER["HTTP_HOST"] n'existe pas
    $urlKey = strtolower($_SERVER["HTTP_HOST"]);
    if (array_key_exists($urlKey, $environments)) {
        $env = $environments[$urlKey];
    }
}

switch ($env) {
    case 'prod' :
        define('YII_ENV', 'prod');
        break;
    default :
        define('YII_ENV', 'dev');
}

defined('YII_DEBUG') or define('YII_DEBUG', YII_ENV == 'dev');

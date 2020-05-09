<?php

$environments = [
    'hypothese-v4' => 'dev',
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
    case 'dev' :
        define('YII_ENV', 'dev');
        defined('YII_DEBUG') or define('YII_DEBUG', true);
        break;
    case 'prod' :
        define('YII_ENV', 'prod');
        defined('YII_DEBUG') or define('YII_DEBUG', true);
        break;
    default :
        define('YII_ENV', '???');
}


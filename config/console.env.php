<?php

$env = '';
if (array_key_exists("argv", $_SERVER)) {
    foreach ($_SERVER['argv'] as $i => $arg) {
        $matches = [];
        if (preg_match('/--env=(\w+)/', $arg, $matches)) {
            $env = $matches[1];
            unset($_SERVER['argv'][$i]);
            break;
        }
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

<?php

// en dev on n'a pas besoin de https + ça ne marche pas (config WAMP ?)
/** @noinspection PhpUndefinedVariableInspection */
unset($config['on beforeRequest']);

$config['name'] = "Hypothese.net @ DEV";

// COMPONENTS

$config['components']['mailer'] = [
    'class' => 'yii\swiftmailer\Mailer',
    // send all mails to a file by default. You have to set
    // 'useFileTransport' to false and configure a transport
    // for the mailer to send real emails.
    'useFileTransport' => true,
];

$config['components']['commandRunner'] = [
    'class' => 'toriphes\console\Runner',
    'phpexec' => 'D:\Dev\Web\wamp\bin\php\php7.1.22\php.exe',
];

// MODULES

$config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
    // uncomment the following to add your IP if you are not connecting from localhost.
    //'allowedIPs' => ['127.0.0.1', '::1'],
];
$config['bootstrap'][] = 'gii';

// PARAMS

//$config['params']['captchaEnabled'] = false;





<?php

// en test on n'a pas besoin de https + ça ne marche pas (config WAMP ?)
/** @noinspection PhpUndefinedVariableInspection */
unset($config['on beforeRequest']);

$config['name'] = "Hypothese.net @ TEST";

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

// PARAMS

$config['params']['captchaEnabled'] = false;




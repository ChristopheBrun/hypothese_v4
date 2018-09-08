<?php

use yii\bootstrap\Alert;

/**
 * Affichage de la barre de notifications
 *
 * @var array $messages [level => [messages]]
 */

foreach ($messages as $level => $flashMessages) :
    foreach ($flashMessages as $flash) :
        /** @noinspection PhpUnhandledExceptionInspection */
        echo Alert::widget([
            'options' => ['class' => 'alert-' . $level],
            'body' => $flash,
        ]);
    endforeach;
endforeach;

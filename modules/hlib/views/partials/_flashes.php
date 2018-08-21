<?php
/**
 * Affichage de la barre de notifications
 *
 * todo_cbn ajouter des entrées pour les différents types de flash : info, success, warning (bootstrap) + danger, dismissable, dismissible (app.css)
 * todo_cbn Passer ça sous la forme d'un widget
 * todo_cbn Mettre ce composant dans HLib
 *
 * @deprecated
 */
use yii\bootstrap\Alert;

$session = Yii::$app->session;
$levels = ['success', 'warning', 'danger'];
foreach ($levels as $level) :
    $key = 'flash-' . $level;
    $flashes = $session->getFlash($key);

    if ($flashes && !is_array($flashes)) {
        $tmp = $flashes;
        $flashes = [$tmp];
    }

    if ($flashes) :
        foreach ($flashes as $msg) :
            echo Alert::widget([
                'options' => ['class' => 'alert-' . $level],
                'body' => $msg,
            ]);
        endforeach;
    endif;
endforeach;

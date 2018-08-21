<?php
use yii\helpers\Html;

/**
 * Lien de suppression d'un objet
 *
 * @var string $url
 * @var string $text
 */

?>

<?= Html::a('<span class="glyphicon glyphicon-trash">', $url, [
    'class' => 'btn btn-danger btn-xs',
    'data' => ['confirm' => $text, 'method' => 'delete',],
]) ?>



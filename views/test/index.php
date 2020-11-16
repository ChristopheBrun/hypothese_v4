<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = "Tests admin";
$this->params['breadcrumbs'][] = ['label' => "Tests", 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="test-index">

    <h1>Tests directs</h1>

    <ul>
        <li><?= Html::a("Envoi d'un email", ['send-email']) ?></li>
        <li><?= Html::a("Appel de Yii::error()", ['yii-error']) ?></li>
    </ul>


</div>

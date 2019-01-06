<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\ephemerides\models\CalendarEntry */

$this->title = Yii::t('labels', 'Create Calendar Entry');
$this->params['breadcrumbs'][] = ['label' => Yii::t('labels', 'Calendar Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calendar-entry-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

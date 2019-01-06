<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\ephemerides\models\CalendarEntry */

$this->title = Yii::t('labels', 'Update Calendar Entry: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('labels', 'Calendar Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('labels', 'Update');
?>
<div class="calendar-entry-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

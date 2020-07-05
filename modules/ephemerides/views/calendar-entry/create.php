<?php

use app\modules\ephemerides\EphemeridesModule;
use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\Tag;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $model CalendarEntry */

$this->title = EphemeridesModule::t('labels', 'Create Calendar Entry');
$this->params['breadcrumbs'][] = ['label' => EphemeridesModule::t('labels', 'Calendar Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calendar-entry-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tags'  => ArrayHelper::map(Tag::find()->all(), 'id', 'label'),
    ]) ?>

</div>

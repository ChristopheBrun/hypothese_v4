<?php

use app\modules\ephemerides\lib\enums\Domaine;
use app\modules\ephemerides\EphemeridesModule;
use app\modules\ephemerides\models\Tag;
use app\modules\hlib\HLib;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $model app\modules\ephemerides\models\CalendarEntry */

$this->title = EphemeridesModule::t('labels', 'Update Calendar Entry: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => EphemeridesModule::t('labels', 'Calendar Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = HLib::t('labels', 'Update');
?>
<div class="calendar-entry-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(HLib::t('labels', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
        'tags' => ArrayHelper::map(Tag::find()->all(), 'id', 'label'),
        'domaines' => Domaine::getList(),
    ]) ?>

</div>

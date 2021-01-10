<?php /** @noinspection PhpUnhandledExceptionInspection */

use app\modules\ephemerides\EphemeridesModule;
use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\widgets\DisplayCalendarEntries;
use app\modules\hlib\HLib;
use yii\helpers\Html;

/* @var $model CalendarEntry */

$this->title = $model->title;

$this->params['breadcrumbs'][] = ['label' => EphemeridesModule::t('labels', 'Calendar Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="calendar-entry-display">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(HLib::t('labels', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(HLib::t('labels', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => HLib::t('labels', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(HLib::t('labels', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </p>

    <?= /** @noinspection PhpUnhandledExceptionInspection */
    DisplayCalendarEntries::widget([
        'models' => [$model],
        'tagsButtonsAltRoute' => ['/site/pas-cliquer'],
    ]) ?>

</div>

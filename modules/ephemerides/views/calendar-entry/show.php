<?php /** @noinspection PhpUnhandledExceptionInspection */

use app\modules\ephemerides\EphemeridesModule;
use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\widgets\DisplayCalendarEntries;
use app\modules\hlib\HLib;
use app\modules\user\lib\enums\AppRole;
use yii\helpers\Html;

/* @var $model CalendarEntry */

$this->title = $model->title;

$this->params['breadcrumbs'][] = ['label' => EphemeridesModule::t('labels', 'Calendar Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="calendar-entry-display">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->can(AppRole::SUPERADMIN)) : ?>
        <p>
            <?= Html::a(HLib::t('labels', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(HLib::t('labels', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => HLib::t('labels', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a("Fiche backend", ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        </p>
    <?php endif ?>

    <?= /** @noinspection PhpUnhandledExceptionInspection */
    DisplayCalendarEntries::widget([
        'models' => [$model],
        'tagsButtonsAltRoute' => ['/site/pas-cliquer'],
    ]) ?>

</div>

<?php /** @noinspection PhpUnhandledExceptionInspection */

use app\modules\ephemerides\EphemeridesModule;
use app\modules\ephemerides\models\CalendarEntry;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $model CalendarEntry */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => EphemeridesModule::t('labels', 'Calendar Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="calendar-entry-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('labels', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('labels', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('labels', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'event_date',
            'title',
            'body:ntext',
            'image',
            'image_caption',
            'enabled',
            'notes:ntext',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

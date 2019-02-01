<?php

use app\modules\ephemerides\models\CalendarEntry;
use app\modules\hlib\helpers\AssetsHelper;
use Carbon\Carbon;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ephemerides\models\form\CalendarEntrySearchForm */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('labels', 'Calendar Entries');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calendar-entry-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a("Ajouter une éphéméride", ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= /** @noinspection PhpUnhandledExceptionInspection */
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => "Date",
                'value' => function (CalendarEntry $model) {
                    return Carbon::parse($model->event_date)->format(CalendarEntry::DATE_FORMAT_DAY);
                },
            ],
            [
                'label' => '',
                'value' => function (CalendarEntry $model) {
                    return Html::img($model->getImageUrl('xs', 'true'));
                },
                'format' => 'html',
            ],
            'title',
            //'image_caption',
            AssetsHelper::gridViewEnabled(),
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

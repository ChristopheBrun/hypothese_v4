<?php

use app\modules\ephemerides\EphemeridesModule;
use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\form\CalendarEntrySearchForm;
use app\modules\ephemerides\models\Tag;
use app\modules\hlib\helpers\AssetsHelper;
use Carbon\Carbon;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $searchModel CalendarEntrySearchForm */
/* @var $dataProvider ActiveDataProvider */
/* @var $tags Tag[] */

$this->title = EphemeridesModule::t('labels', 'Calendar Entries');
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
            [
                'attribute' => 'tag',
                'label' => "Catégories",
                'value' => function (CalendarEntry $model) {
                    return \app\modules\hlib\widgets\DisplayModels::widget([
                        'models' => $model->tags,
                        'labelField' => 'label',
                    ]);
                },
                'format' => 'html',
                'filter' => \yii\helpers\ArrayHelper::map($tags, 'id', 'label'),
            ],
            AssetsHelper::gridViewEnabled(),
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

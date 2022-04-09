<?php

use app\modules\ephemerides\EphemeridesModule;
use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\form\CalendarEntrySearchForm;
use app\modules\ephemerides\models\Tag;
use app\modules\hlib\helpers\AssetsHelper;
use app\modules\hlib\widgets\DisplayModels;
use Carbon\Carbon;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;

/* @var $searchModel CalendarEntrySearchForm */
/* @var $dataProvider ActiveDataProvider */
/* @var $tags Tag[] */
/* @var $filter string */

$this->title = EphemeridesModule::t('labels', 'Calendar Entries');
if (isset($filter)) {
    $this->title .= " : $filter";
}

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="calendar-entry-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a(EphemeridesModule::t('labels', 'Add Calendar Entry'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(EphemeridesModule::t('labels', 'D'), ['index-d'], ['class' => 'btn btn-info']) ?>
        <?= Html::a(EphemeridesModule::t('labels', 'D+1'), ['index-d1'], ['class' => 'btn btn-info']) ?>
        <?= Html::a(EphemeridesModule::t('labels', 'D to D+2'), ['index-d2'], ['class' => 'btn btn-info']) ?>
        <?= Html::a(EphemeridesModule::t('labels', 'Show All'), ['index'], ['class' => 'btn btn-default']) ?>
    </p>

    <?= /** @noinspection PhpUnhandledExceptionInspection */
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'event_date',
                'label' => "Date",
                'value' => function (CalendarEntry $model) {
                    return Carbon::parse($model->event_date)->format(CalendarEntry::DATE_FORMAT_DAY);
                },
                'filter' => MaskedInput::widget(['name' => 'event_date', 'mask' => '99-99']) .
                    "<div class='hint-block'>Filtre : jj-mm</div>",
            ],
            [
                'label' => 'Image',
                'value' => function (CalendarEntry $model) {
                    return $model->image ? Html::img($model->getImageUrl('xs', true)) : '';
                },
                'format' => 'html',
            ],
            'title',
            [
                'attribute' => 'tag',
                'label' => "Catégories",
                'value' => function (CalendarEntry $model) {
                    return DisplayModels::widget([
                        'models' => $model->tags,
                        'labelField' => 'label',
                    ]);
                },
                'format' => 'html',
                'filter' => ArrayHelper::map($tags, 'id', 'label'),
            ],
            AssetsHelper::gridViewEnabled(),

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

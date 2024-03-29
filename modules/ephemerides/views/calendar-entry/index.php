<?php

use app\modules\ephemerides\EphemeridesModule;
use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\form\CalendarEntrySearchForm;
use app\modules\ephemerides\models\Tag;
use app\modules\hlib\helpers\AssetsHelper;
use app\modules\hlib\widgets\DisplayModels;
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
/* @var $dateWithNoEntries string */

$this->title = EphemeridesModule::t('labels', 'Calendar Entries');
if (isset($filter)) {
    $this->title .= " : $filter";
}

$this->params['breadcrumbs'][] = $this->title;

$formattedDateWithNoEntries = implode('-', array_reverse(explode('-', $dateWithNoEntries)));

?>
<div class="calendar-entry-index">
    <?php Pjax::begin(); ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <span class="admin-buttons">
            <?= Html::a(EphemeridesModule::t('labels', 'Add Calendar Entry'), ['create'], ['class' => 'btn btn-success']) ?>
            <?= Html::a(EphemeridesModule::t('labels', 'D'), ['index-d'], ['class' => 'btn btn-info']) ?>
            <?= Html::a(EphemeridesModule::t('labels', 'D+1'), ['index-d1'], ['class' => 'btn btn-info']) ?>
            <?= Html::a(EphemeridesModule::t('labels', 'D to D+2'), ['index-d2'], ['class' => 'btn btn-info']) ?>
            <?= Html::a(EphemeridesModule::t('labels', 'Show All'), ['index'], ['class' => 'btn btn-default']) ?>
        </span>
        <span class="tools-buttons">
            <?= Html::a(EphemeridesModule::t(
                'labels',
                "Next date with 0 entries (dd-mm) : {date}", ['date' => $formattedDateWithNoEntries]),
                ['index'],
                ['class' => 'btn btn-default', 'disabled' => true]
            ) ?>
        </span>
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
                    return DateTimeImmutable::createFromFormat('Y-m-d', $model->event_date)->format(CalendarEntry::DATE_FORMAT_DAY);
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

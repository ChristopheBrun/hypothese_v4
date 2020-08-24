<?php

use app\modules\ephemerides\EphemeridesModule;
use app\modules\ephemerides\lib\enums\Domaine;
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
use yii\widgets\Pjax;

/* @var $searchModel CalendarEntrySearchForm */
/* @var $dataProvider ActiveDataProvider */
/* @var $tags Tag[] */
/* @var $domaines Tag[] */
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
        <?= Html::a(EphemeridesModule::t('labels', 'D to D+2'), ['index-d2'], ['class' => 'btn btn-default']) ?>
        <?= Html::a(EphemeridesModule::t('labels', 'Show All'), ['index'], ['class' => 'btn btn-default']) ?>
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
                'label' => 'Image',
                'value' => function (CalendarEntry $model) {
                    return Html::img($model->getImageUrl('xs', 'true'));
                },
                'format' => 'html',
            ],
            'title',
            [
                'attribute' => 'domaine',
                'label' => "Domaine",
                'value' => function (CalendarEntry $model) {
                    return Domaine::getLabel($model->domaine);
                },
                'format' => 'html',
                'filter' => Domaine::getList(),
            ],
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

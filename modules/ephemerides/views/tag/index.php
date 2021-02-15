<?php

use app\modules\ephemerides\EphemeridesModule;
use app\modules\ephemerides\models\Tag;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var $this yii\web\View */
/** @var $searchModel app\modules\ephemerides\models\form\TagSearchForm */
/** @var $dataProvider yii\data\ActiveDataProvider */

$this->title = EphemeridesModule::t('labels', 'Tags');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a(EphemeridesModule::t('labels', 'Create Tag'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= /** @noinspection PhpUnhandledExceptionInspection */
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'label',
            'rank',
            [
                'label' => "Ephémérides actives",
                'value' => function (Tag $model) {
                    return $model->countEnabledCalendarEntries();
                },
            ],
            [
                'label' => "Ephémérides inactives",
                'value' => function (Tag $model) {
                    return $model->countDisabledCalendarEntries();
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

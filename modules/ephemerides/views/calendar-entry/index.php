<?php

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
        <?= Html::a(Yii::t('labels', 'Create Calendar Entry'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'event_date',
            'title',
            'body:ntext',
            'image',
            //'image_caption',
            //'enabled',
            //'notes:ntext',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

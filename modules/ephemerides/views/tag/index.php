<?php

use app\modules\ephemerides\EphemeridesModule;
use app\modules\hlib\HLib;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var $this yii\web\View */
/** @var $searchModel app\modules\ephemerides\models\form\TagSearchForm */
/** @var $dataProvider yii\data\ActiveDataProvider */

$this->title = EphemeridesModule::t('labels', 'Tags');
$this->params['breadcrumbs'][] = ['label' => HLib::t('labels', 'Management'), 'url' => ['/site/management']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(EphemeridesModule::t('labels', 'Create Tag'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'label',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

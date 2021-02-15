<?php

use app\modules\ephemerides\EphemeridesModule;
use app\modules\hlib\helpers\AssetsHelper;
use app\modules\hlib\HLib;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var $this yii\web\View */
/** @var $model app\modules\ephemerides\models\Tag */

$this->title = sprintf('#%d - %s', $model->id, $model->slug);
$this->params['breadcrumbs'][] = ['label' => EphemeridesModule::t('labels', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="tag-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(HLib::t('labels', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(HLib::t('labels', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => HLib::t('messages', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'label',
            'rank',
            AssetsHelper::detailViewSeparator(),
            [
                'label' => EphemeridesModule::t('labels', 'Nb References'),
                'value' => count($model->calendarEntries),
            ],
            AssetsHelper::detailViewSeparator(),
            'id',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

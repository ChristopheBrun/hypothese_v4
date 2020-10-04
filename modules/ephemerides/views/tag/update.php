<?php

use app\modules\ephemerides\EphemeridesModule;
use app\modules\hlib\HLib;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\ephemerides\models\Tag */

$this->title = EphemeridesModule::t('labels', 'Update Tag: {0}', [sprintf('#%d - %s', $model->id, $model->slug)]);
$this->params['breadcrumbs'][] = ['label' => EphemeridesModule::t('labels', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => sprintf('#%d - %s', $model->id, $model->slug), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = HLib::t('labels', 'Update');
?>
<div class="tag-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php
/**
 * Fiche de consultation d'un objet
 */

use app\modules\cms\HCms;
use app\modules\hlib\widgets\ViewButtons;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\cms\models\Language */

$this->title = HCms::t('labels', 'View language');

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= $this->title ?></h1>
        <h2><?= Html::encode($model->name) ?></h2>
    </div>

    <div class="panel-body">

        <?= ViewButtons::widget([
            'modelId' => $model->id,
            'controllerPath' => '/cms/languages',
        ]) ?>


        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'iso_639_code',
                'created_at',
                'updated_at',
            ],
        ]) ?>

    </div>
</div>

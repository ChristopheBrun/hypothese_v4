<?php

/**
 * Formulaire de mise à jour
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BaseNews;
use app\modules\cms\widgets\BaseNewsForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var BaseNews     $model
 */

$this->title = HCms::t('labels', 'Update base news : {label}', ['label' => $model->event_date]);

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <?= BaseNewsForm::widget(['model' => $model]) ?>
    </div>
</div>

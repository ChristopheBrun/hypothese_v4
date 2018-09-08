<?php
/**
 * @var $model \yii\db\ActiveRecord
 * @var $form \yii\widgets\ActiveForm
 * @var $label string
 * @var $dayField string
 * @var $monthField string
 * @var $yearField string
 * @var $checkActive bool
 */

use app\modules\hlib\lib\ActiveFieldAttributeOptions;
use app\modules\hlib\lib\enums\Month;
use app\modules\hlib\lib\ranges\Years;
use app\modules\hlib\lib\ranges\Days;
use yii\helpers\Html;

$denominator = 1;
$dayField && ++$denominator;
$monthField && ++$denominator;
$colWidth = 12 / $denominator;

$required = $model->isAttributeRequired($dayField) || $model->isAttributeRequired($monthField) || $model->isAttributeRequired($yearField);
$enable = $checkActive ? new ActiveFieldAttributeOptions(['model' => $model]) : null;
?>

<div class="row <?= $required ? 'required' : '' ?>">
    <div class="col-md-12">
        <?= Html::label($label, null, ['class' => 'control-label']) ?>
    </div>

    <?php if ($dayField) : ?>
        <div class="col-md-<?= $colWidth ?>">
            <?= $form->field($model, $dayField)->label(false)
                ->dropDownList((new Days())->options(), $enable ? $enable->disable($dayField) : []) ?>
        </div>
    <?php endif ?>

    <?php if ($monthField): ?>
        <div class="col-md-<?= $colWidth ?>">
            <?= $form->field($model, $monthField)->label(false)
                ->dropDownList(Month::getList(), $enable ? $enable->disable($monthField) : []) ?>
        </div>
    <?php endif ?>

    <div class="col-md-<?= $colWidth ?>">
        <?= $form->field($model, $yearField)->label(false)
            ->dropDownList((new Years())->options(), $enable ? $enable->disable($yearField) : []) ?>
    </div>

</div>
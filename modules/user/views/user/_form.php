<?php

use app\modules\hlib\HLib;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\form\CreateUserForm */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model->user, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model->profile, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model->profile, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? HLib::t('labels', 'Create') : HLib::t('labels', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

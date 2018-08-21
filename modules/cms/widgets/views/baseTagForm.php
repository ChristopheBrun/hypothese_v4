<?php
/**
 * Formulaire pour la création ou la modification d'un BaseTag
 */

use app\modules\cms\models\BaseTag;
use app\modules\hlib\HLib;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var BaseTag     $model
 * @var boolean      $asNestedForm
 *
 * @var ActiveForm   $form
 */

if (!isset($noButtons)) {
    $noButtons = false;
}

?>
<div class="backend-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')
        ->textInput(['maxlength' => true]) ?>

    <?php if (!$asNestedForm) : ?>
        <div class="form-group">
            <?= Html::submitButton(HLib::t('labels', 'Save'), [
                'class' => 'btn btn-success',
                'name' => 'action',
                'value' => 'save']) ?>

            <?= Html::a(HLib::t('labels', 'Cancel'), Url::to(['/cms/web-tags/index']), [
                'class' => 'btn btn-warning',
                'name' => 'action',
                'value' => 'cancel']) ?>
        </div>
    <?php endif ?>

    <?php if (!$asNestedForm) ActiveForm::end(); ?>

</div>

<?php
/**
 * Formulaire pour la création ou la modification d'un BaseText
 */

use app\modules\cms\models\BaseText;
use app\modules\hlib\HLib;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var BaseText     $model
 * @var boolean      $asNestedForm
 * @var array        $basePages BasePage[]
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

    <?= $form->field($model, 'base_page_id')
        ->dropDownList(ArrayHelper::map($basePages, 'id', 'code')) ?>

    <?php if (!$asNestedForm) : ?>
        <div class="form-group">
            <?= Html::submitButton(HLib::t('labels', 'Save'), [
                'class' => 'btn btn-success',
                'name' => 'action',
                'value' => 'save']) ?>

            <?= Html::a(HLib::t('labels', 'Cancel'), Url::to(['/cms/web-texts/index']), [
                'class' => 'btn btn-warning',
                'name' => 'action',
                'value' => 'cancel']) ?>
        </div>
    <?php endif ?>

    <?php if (!$asNestedForm) ActiveForm::end(); ?>

</div>

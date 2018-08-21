<?php
/**
 * Formulaire pour la création ou la modification de l'objet
 */
use app\modules\hlib\widgets\SubmitButtons;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model app\modules\cms\models\Language
 * @var $form yii\widgets\ActiveForm
 * @var $parameters array
 */
?>

<div class="role-form">

    <?php $form = ActiveForm::begin($parameters); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'iso_639_code')->textInput(['maxlength' => true]) ?>

    <?= SubmitButtons::widget(['indexUrl' => Url::to(['/cms/languages/index'], true)]) ?>

    <?php ActiveForm::end(); ?>

</div>

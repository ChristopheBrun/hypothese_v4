<?php
/**
 * Formulaire pour la création ou la modification de l'objet
 */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model app\models\Tag
 * @var $form yii\widgets\ActiveForm
 * @var $parameters array
 */
?>

<div class="tag-form">

    <?php $form = ActiveForm::begin($parameters); ?>

    <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

    <?=
    $this->render('@app/modules/hlib/views/partials/_formSubmitButtons', [
        'indexUrl' => Url::to(['/tags/index', 'page' => 1]),
        'deleteUrl' => Url::to(['/tags/delete', 'id' => $model->id], true), // NB : ne fonctionne pas pour le moment
    ])
    ?>

    <?php ActiveForm::end(); ?>

</div>

<?php

/**
 * Formulaire pour la création ou la modification de l'objet
 */

use app\modules\cms\models\BaseText;
use app\modules\cms\models\WebText;
use app\modules\cms\widgets\BaseTextForm;
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\SubmitButtons;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\redactor\widgets\Redactor;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var WebText      $model
 * @var BaseText     $baseModel
 * @var ActiveForm   $form
 * @var array        $parameters
 * @var array        $baseTexts [BaseText]
 * @var array        $languages [Language]
 */

// Quand le texte/racine sélectionné est modifié, on recharge le sous-formulaire associé
$this->registerJs("
    $('#webtext-base_id').change(function() {
        var url = '/cms/base-texts/get-form/' + $(this).val();
        $('#base-model-display').load(url);
    });
");

?>

<div class="backend-form">

    <?php $form = ActiveForm::begin($parameters); ?>

    <?= $form->field($model, 'base_id')
        ->dropDownList(ArrayHelper::map($baseTexts, 'id', 'code'), ['prompt' => HLib::t('messages', 'Select a value in the list')]) ?>

    <fieldset id="base-model-display">
        <?= BaseTextForm::widget(['model' => $baseModel, 'asNestedForm' => true]) ?>
    </fieldset>

    <fieldset>

        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'title')
                    ->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'subtitle')
                    ->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'language_id')
                    ->dropDownList(ArrayHelper::map($languages, 'id', 'name')) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <?= $form->field($model, 'description')
                    ->textarea(['maxlength' => true]) ?>
            </div>
        </div>

        <?= $form->field($model, 'body')
            ->widget(Redactor::class, [
                'clientOptions' => [
                    'plugins' => ['clips', 'fontcolor', 'imagemanager']
                ],
            ]) ?>

        <?= SubmitButtons::widget(['indexUrl' => Url::to(['/cms/web-texts/index'], true)]) ?>

    </fieldset>

    <?php ActiveForm::end(); ?>

</div>

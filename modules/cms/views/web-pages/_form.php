<?php

/**
 * Formulaire pour la création ou la modification de l'objet
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BasePage;
use app\modules\cms\models\WebPage;
use app\modules\cms\widgets\BasePageForm;
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\SubmitButtons;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var WebPage      $model
 * @var BasePage     $baseModel
 * @var ActiveForm   $form
 * @var array        $basePages [BasePage]
 * @var array        $languages [Language]
 */

// Quand la page/racine sélectionnée est modifiée, on recharge le sous-formulaire associé
// NB : il faut aussi recharger le select2 associé à cette liste déroulante
$placeHolderMsg = HCms::t('messages', 'Select associated base texts if necessary');
$this->registerJs("
    $('#webpage-base_id').change(function() {
        var url = '/cms/base-pages/get-form/' + $(this).val();
        $('#base-model-display').load(url, function() {
            $('#base_texts_ids').select2({
                placeholder: \"$placeHolderMsg\",
                tags: true
            });
        });
    });
");

?>

<div class="backend-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'base_id')
        ->dropDownList(ArrayHelper::map($basePages, 'id', 'code'), ['prompt' => HLib::t('messages', 'Select a value in the list')]) ?>

    <fieldset id="base-model-display">
        <?= BasePageForm::widget(['model' => $baseModel, 'asNestedForm' => true]) ?>
    </fieldset>

    <fieldset>
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'title')
                    ->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'menu_title')
                    ->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'language_id')
                    ->dropDownList(ArrayHelper::map($languages, 'id', 'name'), ['prompt' => HLib::t('messages', 'Select a value in the list')]) ?>
            </div>
        </div>

        <?= $form->field($model, 'meta_description')
            ->textarea(['maxlength' => true]) ?>

        <?= $form->field($model, 'meta_keywords')
            ->textInput(['maxlength' => true]) ?>

        <?= SubmitButtons::widget(['indexUrl' => Url::to(['/cms/web-pages/index'], true)]) ?>
    </fieldset>

    <?php ActiveForm::end(); ?>

</div>

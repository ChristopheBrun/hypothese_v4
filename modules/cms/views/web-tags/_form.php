<?php
/**
 *
 */

use app\modules\cms\models\WebTag;
use app\modules\cms\models\BaseTag;
use app\modules\cms\widgets\BaseTagForm;
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\SubmitButtons;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var WebTag       $model
 * @var BaseTag      $baseModel
 * @var array        $baseTags [BaseTag]
 * @var array        $languages [Language]
 *
 * @var ActiveForm   $form
 */

// Quand le tag/racine sélectionné est modifié, on recharge le sous-formulaire associé
$this->registerJs("
    $('#webtag-base_id').change(function() {
        var url = '/cms/base-tags/get-form/' + $(this).val();
        $('#base-model-display').load(url);
    });
");
?>

<div class="backend-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'base_id')
        ->dropDownList(ArrayHelper::map($baseTags, 'id', 'code'), ['prompt' => HLib::t('messages', 'Select a value in the list')]) ?>

    <fieldset id="base-model-display">
        <?= BaseTagForm::widget(['model' => $baseModel, 'asNestedForm' => true]) ?>
    </fieldset>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'language_id')
                ->dropDownList(ArrayHelper::map($languages, 'id', 'iso_639_code'), ['prompt' => HLib::t('messages', 'Select a value in the list')]) ?>
        </div>

        <div class="col-sm-6">
            <?= $form->field($model, 'label')
                ->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?= SubmitButtons::widget(['indexUrl' => Url::to(['/cms/web-tags/index'], true)]) ?>

    <?php ActiveForm::end(); ?>

</div>

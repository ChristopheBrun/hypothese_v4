<?php
/**
 * Formulaire pour la création ou la modification de l'objet
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BaseNews;
use app\modules\cms\models\WebNews;
use app\modules\cms\widgets\BaseNewsForm;
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\SubmitButtons;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\redactor\widgets\Redactor;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var WebNews      $model
 * @var BaseNews     $baseModel
 * @var array        $baseNews [BaseNews]
 * @var array        $languages [Language]
 *
 * @var ActiveForm   $form
 */

// Quand la news/racine sélectionnée est modifiée, on recharge le sous-formulaire associé
// NB : il faut aussi recharger le select2 associé à cette liste déroulante
$placeHolderMsg = HCms::t('messages', 'Select associated base tags if necessary');
$this->registerJs("
    $('#webnews-base_id').change(function() {
        var url = '/cms/base-news/get-form/' + $(this).val();
        $('#base-model-display').load(url, function() {
            $('#base_tags_ids').select2({
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
        ->dropDownList(ArrayHelper::map($baseNews, 'id', 'event_date'), ['prompt' => HLib::t('messages', 'Select a value in the list')]) ?>

    <fieldset id="base-model-display">
        <?= BaseNewsForm::widget(['model' => $baseModel, 'asNestedForm' => true]) ?>
    </fieldset>

    <fieldset>
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'language_id')
                    ->dropDownList(ArrayHelper::map($languages, 'id', 'iso_639_code'), ['prompt' => HLib::t('messages', 'Select a value in the list')]) ?>
            </div>

            <div class="col-sm-4">
                <?= $form->field($model, 'title')
                    ->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-sm-4">
                <?= $form->field($model, 'description')
                    ->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <?= $form->field($model, 'body')
            ->widget(Redactor::class, ['clientOptions' => ['plugins' => ['clips', 'fontcolor', 'imagemanager']]]) ?>

        <?= SubmitButtons::widget(['indexUrl' => Url::to(['/cms/web-news/index'], true)]) ?>
    </fieldset>

    <?php ActiveForm::end(); ?>

</div>

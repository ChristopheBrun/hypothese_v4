<?php

/**
 * Formulaire pour la création ou la modification d'une BasePage
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BasePage;
use app\modules\hlib\HLib;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var BasePage     $model
 * @var array        $baseTexts [BaseText]
 * @var array        $parentPages [BasePage]
 * @var boolean      $asNestedForm
 *
 * @var ActiveForm   $form
 */

if (!isset($noButtons)) {
    $noButtons = false;
}

// Ajout du Select2 pour gérer les textes
$this->registerJs('
    $(\'#base_texts_ids\').select2({
        placeholder: "' . HCms::t('messages', 'Select associated base texts if necessary') . '",
        tags: true
    });
');

?>
<div class="backend-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'code')
                ->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?=
            $form->field($model, 'parent_page_id')
                ->dropDownList(ArrayHelper::map($parentPages, 'id', 'code'), ['prompt' => HCms::t('messages', 'Select a parent page if necessary')]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'enabled')
                ->textInput()->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'menu_index')
                ->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'route')
                ->textInput(['maxlength' => true])
                ->hint($model->route ? HCms::t('labels', 'URL : {url}', ['url' => Url::to([$model->route], true)]) : '') ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'redirect_to')
                ->textInput(['maxlength' => true])
                ->hint($model->redirect_to ? HCms::t('labels', 'URL : {url}', ['url' => Url::to([$model->redirect_to], true)]) : '') ?>
        </div>
    </div>

    <?= $form->field($model, 'baseTexts')
        ->listBox(ArrayHelper::map($baseTexts, 'id', 'code'), ['id' => 'base_texts_ids', 'multiple' => true]) ?>

    <?php if (!$asNestedForm) : ?>
        <div class="form-group">
            <?= Html::submitButton(HLib::t('labels', 'Save'), [
                'class' => 'btn btn-success',
                'name' => 'action',
                'value' => 'save']) ?>

            <?= Html::a(HLib::t('labels', 'Cancel'), Url::to(['/cms/web-pages/index']), [
                'class' => 'btn btn-warning',
                'name' => 'action',
                'value' => 'cancel']) ?>
        </div>
    <?php endif ?>

    <?php if (!$asNestedForm) ActiveForm::end(); ?>

</div>

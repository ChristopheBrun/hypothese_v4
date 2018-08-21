<?php

/**
 * Formulaire pour la création ou la modification d'une BaseNews
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BaseNews;
use app\modules\hlib\HLib;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var BaseNews     $model
 * @var array        $baseTags [BaseTag]
 * @var boolean      $asNestedForm
 *
 * @var ActiveForm   $form
 */

if (!isset($noButtons)) {
    $noButtons = false;
}

// Ajout du Select2 pour gérer les tags
$this->registerJs('
    $(\'#base_tags_ids\').select2({
        placeholder: "' . HCms::t('messages', 'Select associated base tags if necessary') . '",
        tags: true
    });
');

?>
<div class="backend-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'enabled')
                ->textInput()->checkbox() ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'event_date')
                ->widget(DatePicker::class, ['dateFormat' => 'dd-MM-yyyy']) ?>
        </div>
    </div>

    <?= $form->field($model, 'baseTags')
        ->listBox(ArrayHelper::map($baseTags, 'id', 'code'), ['id' => 'base_tags_ids', 'multiple' => true]) ?>

    <?php if (!$asNestedForm) : ?>
        <div class="form-group">
            <?= Html::submitButton(HLib::t('labels', 'Save'), [
                'class' => 'btn btn-success',
                'name' => 'action',
                'value' => 'save']) ?>

            <?= Html::a(HLib::t('labels', 'Cancel'), Url::to(['/cms/web-news/index']), [
                'class' => 'btn btn-warning',
                'name' => 'action',
                'value' => 'cancel']) ?>
        </div>
    <?php endif ?>

    <?php if (!$asNestedForm) ActiveForm::end(); ?>

</div>

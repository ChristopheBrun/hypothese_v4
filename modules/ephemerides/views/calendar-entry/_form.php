<?php /** @noinspection PhpUnhandledExceptionInspection */

use app\modules\ephemerides\EphemeridesModule;
use app\modules\hlib\HLib;
use kartik\select2\Select2;
use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $model app\modules\ephemerides\models\CalendarEntry */
/* @var $form yii\widgets\ActiveForm */
/* @var $tags array [id => label] liste des tags disponibles */

?>

<div class="calendar-entry-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'event_date')->textInput(['type' => 'date'])
        ->widget(MaskedInput::class, ['mask' => '9999-99-99']) ?>

    <?= $form->field($model, 'title')->textarea() ?>

    <?= $form->field($model, 'description')->widget(Widget::class, ['settings' => [
        'minHeight' => '200px',
    ]]) ?>

    <?= $form->field($model, 'twitter')->textarea() ?>

    <?= $form->field($model, 'notes')->widget(Widget::class)
        ->hint(EphemeridesModule::t('messages', 'Notes are for redactors only, they are not published')) ?>

    <?php if ($model->body) : ?>
        <?= $form->field($model, 'body')->widget(Widget::class) ?>
    <?php endif ?>

    <div class="form-group">
        <?= Html::label(EphemeridesModule::t('labels', 'Current image') . ' : ' . $model->image,
            '', ['class' => 'control-label']) ?>
        <br/>

        <?php if ($model->hasImage()) : ?>
            <?= Html::img($model->getImageUrl('std', true), ['alt' => $model->getSlug()]) ?>
            <?= $form->field($model, 'deleteImage')->checkbox() ?>
        <?php else : ?>
            <div><?= Yii::t('messages', 'There is no image for this object') ?></div>
        <?php endif ?>
    </div>

    <?= $form->field($model, 'uploadedImage')->fileInput() ?>

    <?= $form->field($model, 'image_caption') ?>

    <?= $form->field($model, 'source_image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tags')->widget(Select2::class, [
        'data' => $tags,
        'options' => ['multiple' => true],
        'pluginOptions' => ['tags' => true, 'maximumInputLength' => 5],
    ]) ?>

    <?= $form->field($model, 'enabled')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(HLib::t('labels', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

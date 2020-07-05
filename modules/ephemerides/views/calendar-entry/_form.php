<?php /** @noinspection PhpUnhandledExceptionInspection */

use app\modules\ephemerides\EphemeridesModule;
use app\modules\hlib\HLib;
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

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'enabled')->checkbox() ?>

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

    <?= $form->field($model, 'tags')->listBox($tags, ['id' => 'tags_ids', 'multiple' => true]) ?>

    <?= $form->field($model, 'notes')->textarea()->hint(EphemeridesModule::t('messages',
        'Notes are for redactors only, they are not published')) ?>

    <div class="form-group">
        <?= Html::submitButton(HLib::t('labels', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

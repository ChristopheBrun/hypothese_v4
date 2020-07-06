<?php
/**
 * Formulaire pour la création ou la modification de l'objet
 */

use app\modules\ephemerides\EphemeridesModule;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model app\models\CalendarEntry
 * @var $form yii\widgets\ActiveForm
 * @var $parameters array
 * @var $tags array [id => label] liste des tags disponibles
 */

// Ajout du Select2 pour gérer les tags
$this->registerJs('
    $(\'#tags_ids\').select2({
        placeholder: "' . Yii::t('labels', 'Add tags') . '",
        tags: true
    });
');

$parameters = array_merge($parameters, ['options' => ['enctype' => 'multipart/form-data']]);
?>

<div class="model-form">

    <?php $form = ActiveForm::begin($parameters); ?>

    <?= $form->field($model, 'event_date')->textInput(['type' => 'date'])->widget(\yii\widgets\MaskedInput::class, ['mask' => '9999-99-99']) ?>

    <?= $form->field($model, 'enabled')->checkbox() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::label(Yii::t('labels', 'Current image') . ' : ' . $model->image, '', ['class' => 'control-label']) ?>
        <br/>

        <?php /** @noinspection PhpUndefinedMethodInspection */
        if ($model->hasImage()) : ?>

            <?= /** @noinspection PhpUndefinedMethodInspection */
            Html::img($model->getImageUrl('std', true), ['alt' => $model->getSlug()]) ?>
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

    <?= $this->render('@app/modules/hlib/views/partials/_formSubmitButtons', ['indexUrl' => Url::to(['/calendar-entries/index', 'page' => 1])]) ?>

    <?php if (!$model->isNewRecord) : ?>
        <div class="form-group">
            <?php
            echo Html::a(Yii::t('labels', 'View model (backend)'),
                Url::to(['/calendar-entries/view', 'id' => $model->id]), [
                    'class' => 'btn btn-info',
                    'name' => 'action',
                    'value' => 'cancel'
                ]
            );

            if ($model->enabled) {
                echo Html::a(Yii::t('labels', 'Show model (frontend)'),
                    Url::to(['/calendar-entries/show', 'id' => $model->id, 'slug' => $model->getSlug()]), [
                        'class' => 'btn btn-default',
                        'name' => 'action',
                        'value' => 'cancel'
                    ]
                );
            }
            ?>
        </div>
    <?php endif ?>

    <?php ActiveForm::end(); ?>

</div>

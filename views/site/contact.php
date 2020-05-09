<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\ContactForm */

/* @var $formSubmitted boolean */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default" id="contact-page">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <?php if ($formSubmitted): ?>
            <div class="row alert alert-success">
                Merci pour votre message. Je vous répondrai dès que possible.
            </div>
        <?php endif ?>
        <div class="row">
            <div class="col-sm-6">
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'name')->textInput(['autofocus' => true, 'readonly' => $formSubmitted]) ?>

                <?= $form->field($model, 'email')->textInput(['readonly' => $formSubmitted]) ?>

                <?= $form->field($model, 'subject')->textInput(['readonly' => $formSubmitted]) ?>

                <?= $form->field($model, 'body')->textarea(['rows' => 6, 'readonly' => $formSubmitted]) ?>

                <?= /** @noinspection PhpUnhandledExceptionInspection */
                $formSubmitted ? '' : $form->field($model, 'verifyCode')->widget(Captcha::class, [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'sendMessage'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="col"></div>
        </div>
    </div>

    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-12">
            </div>
        </div>
    </div>
</div>



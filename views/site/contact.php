<?php

use app\models\ContactForm;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $model ContactForm */
/* @var boolean $formSubmitted */

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
        <div class="row">
            <div class="col-sm-6">
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'name')->textInput(['autofocus' => true, 'readonly' => $formSubmitted]) ?>

                <?= $form->field($model, 'email')->textInput(['readonly' => $formSubmitted]) ?>

                <?= $form->field($model, 'subject')->textInput(['readonly' => $formSubmitted]) ?>

                <?= $form->field($model, 'body')->textarea(['rows' => 6, 'readonly' => $formSubmitted]) ?>

                <?= $formSubmitted ? '' : $form->field($model, 'verifyCode')->widget(Captcha::class, [
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



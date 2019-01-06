<?php
use app\modules\cms\models\WebPage;
use app\modules\cms\models\WebText;
use app\modules\hlib\HLib;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/**
 * @var         $this yii\web\View
 * @var         $form yii\bootstrap\ActiveForm
 * @var         $model app\models\ContactForm
 * @var WebPage $page
 */

$this->title = $page->title;
$this->registerMetaTag(['description' => $page->meta_description]);
/** @var WebText $text */
$text = $page->getText('Contact', new WebText(['title' => '??', 'body' => '??']));
?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1><?= $text->title ?></h1>
        </div>

        <div class="panel-body">
            <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

                <div class="alert alert-success">
                    Merci de nous avoir contactés. Nous vous répondrons dans les meilleurs délais.
                </div>

            <?php else: ?>

                <?= $text->body ?>

                <div class="row">
                    <div class="col-lg-5">
                        <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                        <?= $form->field($model, 'name') ?>

                        <?= $form->field($model, 'email') ?>

                        <?= $form->field($model, 'subject') ?>

                        <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>

                        <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                        ]) ?>

                        <div class="form-group">
                            <?= Html::submitButton(HLib::t('labels', 'Submit'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>

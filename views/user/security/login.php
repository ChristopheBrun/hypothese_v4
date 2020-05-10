<?php

/**
 * Affichage du formulaire d'authentification
 */

/** @var $model app\modules\user\models\form\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\user\UserModule;

$this->title = UserModule::t('labels', 'Sign In');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="panel panel-default" id="login-page">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12">
                <h1><?= UserModule::t('messages', "Please authenticate yourself") ?></h1>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-sm-6">
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                ]); ?>

                <?= $form->field($model, 'email')->textInput() ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= Html::submitButton(UserModule::t('labels', 'Sign In'), ['class' => 'btn btn-success btn-block']) ?>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="col"></div>
        </div>
        <div class="row">
            <div class="col-sm-6 request-new-password">
                <p class="text-center">
                    <?= Html::a(UserModule::t('messages', 'Lost password ?'), ['/user/security/request-new-password']) ?>
                </p>
            </div>
            <div class="col">
            </div>
        </div>
        <?php if (UserModule::getInstance()->registration_confirmationRequired) : ?>
            <div class="row">
                <div class="col-sm-6 request-new-confirmation-link">
                    <p class="text-center">
                        <?= Html::a(UserModule::t('messages', 'Lost confirmation mail ?'), ['/user/security/request-new-confirmation-link']) ?>
                    </p>
                </div>
                <div class="col">
                </div>
            </div>
        <?php endif ?>
    </div>

    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-12">

            </div>
        </div>
    </div>
</div>

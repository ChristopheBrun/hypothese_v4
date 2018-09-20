<?php

/**
 * Formulaire de saisie du mot de passe
 *
 * @var $this yii\web\View
 * @var $model \app\modules\user\models\form\PasswordForm
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\modules\user\UserModule;
use app\modules\hlib\HLib;

$this->title = UserModule::t('labels', 'Create password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= UserModule::t('labels', "Password creation") ?></h3>
            </div>

            <div class="panel-body">
                <?php $form = ActiveForm::begin(); ?>

                <div class="notes">
                    <?= UserModule::t('messages', "Your password must have at least {0} characters", [UserModule::getInstance()->passwordMinLength]) ?>
                </div>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('<i class="fa fa-save"></i>' . HLib::t('labels', 'Save'), ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

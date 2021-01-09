<?php

use app\models\ConsoleCommandForm;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/**
 * @var $model ConsoleCommandForm
 * @var $consoleOutput string
 */

$this->title = Yii::t('app', 'yiiCommands');
$this->title = "Commandes";
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="panel panel-default" id="homepage">
        <div class="panel-heading">
            <h1><?= $this->title ?></h1>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <?php $form = ActiveForm::begin(['id' => 'command-form']); ?>

                    <?= $form->field($model, 'command')->textInput(['autofocus' => true]) ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'sendCommand'), ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?= $consoleOutput ?>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-12">

                </div>
            </div>
        </div>
    </div>
</div>


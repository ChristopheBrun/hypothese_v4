<?php

use yii\helpers\Html;

$this->title = Yii::$app->name;

?>

<div class="panel panel-default" id="utilitaires-path-windows">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12">
                <h1>Pense-bête</h1>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <ul>
                    <li><?= Html::a('Console Windows / Power Shell', ['/memos/console-windows']) ?></li>
                    <li><?= Html::a('Configuration PHP', ['/memos/config-php']) ?></li>
                </ul>
            </div>

        </div>

        <?= $this->render('/site/_inner-footer') ?>

    </div>
</div>


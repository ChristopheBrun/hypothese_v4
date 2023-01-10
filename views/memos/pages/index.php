<?php

use yii\helpers\Html;

$this->title = "Pense-bête";
$this->params['breadcrumbs'][] = $this->title;
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
Yii::$app->addKeywordsMetaTag('memos');

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12">
                <h1><?= $this->title ?></h1>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <ul>
                    <li><?= Html::a('Console Windows / Power Shell', ['/memos/console-windows']) ?></li>
                    <li><?= Html::a('Configuration PHP', ['/memos/config-php']) ?></li>
                    <li><?= Html::a('Commandes Git', ['/memos/serveur-php']) ?></li>
                </ul>
            </div>

        </div>

        <?= $this->render('/site/_inner-footer') ?>

    </div>
</div>


<?php

use yii\helpers\Html;

$this->title = "Utilitaires";
$this->params['breadcrumbs'][] = $this->title;
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
Yii::$app->addKeywordsMetaTag('utilitaires');

?>

<div class="panel panel-default" id="utilitaires-index">
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
                    <li><?= Html::a('Path Windows > Linux', ['/utilitaires/path-windows']) ?></li>
                    <li><?= Html::a('Expressions régulières', ['/utilitaires/regex']) ?></li>
                </ul>
            </div>

        </div>

        <?= $this->render('/site/_inner-footer') ?>

    </div>
</div>


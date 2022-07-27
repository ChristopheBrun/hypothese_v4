<?php

use yii\bootstrap\Html;
use yii\web\JqueryAsset;

$this->title = "Encodages et décodages depuis PHP";
$this->params['breadcrumbs'][] = ['label' => 'Utilitaires', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
Yii::$app->addKeywordsMetaTags(['encode', 'decode']);

$this->registerJsFile('@web/js/encode-decode.js', ['depends' => [JqueryAsset::class]]);

?>

<div class="panel panel-default" id="utilitaires-path-windows">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12">
                <h1><?= $this->title ?></h1>
            </div>
        </div>
    </div>

    <div class="panel-body">

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="base64-encode">Base64_encode</label>
                    <?= Html::textInput('base64-encode', null, ['class' => "form-control", 'id' => 'base64-encode']) ?>
                </div>
                <div class="form-group">
                    <button class="btn btn-success" id="base64-encode-submit">Envoyer</button>
                </div>
                <div class="form-group">
                    <?= Html::textInput('base64-encode-resultat', null, ['class' => "form-control", 'id' => 'base64-encode-resultat']) ?>
                </div>
                <div class="form-group">
                    <button class="btn btn-default" id="base64-encode-copy">Copier dans le presse-papier</button>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="base64-decode">Base64_decode</label>
                    <?= Html::textInput('base64-decode', null, ['class' => "form-control", 'id' => 'base64-decode']) ?>
                </div>
                <div class="form-group">
                    <button class="btn btn-success" id="base64-decode-submit">Envoyer</button>
                </div>
                <div class="form-group">
                    <?= Html::textInput('base64-decode-resultat', null, ['class' => "form-control", 'id' => 'base64-decode-resultat']) ?>
                </div>
                <div class="form-group">
                    <button class="btn btn-default" id="base64-decode-copy">Copier dans le presse-papier</button>
                </div>
            </div>

        </div>
    </div>

    <?= $this->render('/site/_inner-footer') ?>

</div>


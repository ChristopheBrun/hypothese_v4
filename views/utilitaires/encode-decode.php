<?php

use yii\bootstrap\Html;
use yii\web\JqueryAsset;

/**
 * @var array $listEncodeOptions
 * @var array $listDecodeOptions
 */

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
                    <label for="encode">Fonction 'encode'</label>
                    <?= Html::dropDownList('list-encode', null, $listEncodeOptions, ['id' => 'list-encode']) ?>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="decode">Fonction 'decode'</label>
                    <?= Html::dropDownList('list-decode', null, $listDecodeOptions, ['id' => 'list-decode']) ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <?= Html::textarea('encode', null, ['class' => "form-control", 'id' => 'encode']) ?>
                </div>
                <div class="form-group">
                    <button class="btn btn-success" id="encode-submit">Envoyer</button>
                </div>
                <div class="form-group">
                    <?= Html::textarea('encode-resultat', null, ['class' => "form-control", 'id' => 'encode-resultat']) ?>
                </div>
                <div class="form-group">
                    <button class="btn btn-default" id="encode-copy">Copier dans le presse-papier</button>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <?= Html::textarea('decode', null, ['class' => "form-control", 'id' => 'decode']) ?>
                </div>
                <div class="form-group">
                    <button class="btn btn-success" id="decode-submit">Envoyer</button>
                </div>
                <div class="form-group">
                    <?= Html::textarea('decode-resultat', null, ['class' => "form-control", 'id' => 'decode-resultat']) ?>
                </div>
                <div class="form-group">
                    <button class="btn btn-default" id="decode-copy">Copier dans le presse-papier</button>
                </div>
            </div>

        </div>
    </div>

    <?= $this->render('/site/_inner-footer') ?>

</div>


<?php

use yii\bootstrap\Html;
use yii\web\JqueryAsset;
use yii\web\View;

$this->title = "Path windows pour console Linux";
$this->params['breadcrumbs'][] = ['label' => 'Utilitaires', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
Yii::$app->addKeywordsMetaTags(['Windows', 'Linux', 'Unix', "path"]);

$this->registerJsFile('@web/js/path-windows.js', ['depends' => [JqueryAsset::class]]);
// @see https://highlightjs.org/download/
$this->registerCssFile('@web/js/highlight/styles/agate.css');
$this->registerJsFile('@web/js/highlight/highlight.pack.js');
$this->registerJs(<<<JS
    // noinspection JSUnresolvedFunction,JSUnresolvedVariable
    hljs.initHighlightingOnLoad();
JS, View::POS_END);

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
            <div class="col-sm-12 form-group">
                <label for="path-windows">Copier le path ici</label>
                <?= Html::textInput('path-windows', null, ['class' => "form-control", 'id' => 'path-windows']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 form-group">
                <button class="btn btn-success" id="path-windows-submit">Envoyer</button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 form-group">
                <?= Html::textInput('path-windows-resultat', null, ['class' => "form-control", 'id' => 'path-windows-resultat']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 form-group">
                <button class="btn btn-default" id="path-windows-copy">Copier dans le presse-papier</button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 explications">
                <ul>
                    <li>Prend un path Windows en entrée.</li>
                    <li>Renvoie un path "Linux like" en remplaçant les anti-slashes \ par des slashes / et en protégeant
                        le path avec des guillemets s'il contient des espaces.
                    </li>
                    <li>Le traitement se fait en javascript dans le navigateur :</li>
                </ul>
            </div>
        </div>
        <pre>
            <code>
                $('#path-windows-submit').on('click', function () {
                    let path = $('#path-windows').val();
                    let out = path.replace(/\\/g, '/');
                    if (path.includes(' ')) {
                        out = '"' + out + '"';
                    }

                    $('#path-windows-resultat').val(out);
                });
            </code>
        </pre>
    </div>

    <?= $this->render('/site/_inner-footer') ?>

</div>


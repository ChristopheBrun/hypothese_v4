<?php
/**
 * Affichage d'un path linux correct
 */


use yii\bootstrap\Html;
use yii\web\JqueryAsset;

$this->title = Yii::$app->name;
$this->registerJsFile('@web/js/path-windows.js', ['depends' => [JqueryAsset::class]]);
// @see https://highlightjs.org/download/
$this->registerJsFile('@web/js/highlight/highlight.pack.js');
$this->registerCssFile('@web/js/highlight/styles/default.css');

?>

<div class="panel panel-default" id="utilitaires-path-windows">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12">
                <h1>Path windows pour console Linux</h1>
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
                <pre>
                    <code class="javascript">
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
        </div>
    </div>

    <?= $this->render('/site/_inner-footer') ?>

</div>


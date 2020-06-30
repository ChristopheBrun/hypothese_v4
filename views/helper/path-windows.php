<?php
/**
 * Created by PhpStorm.
 * User: Christophe
 * Date: 30/06/2020
 * Time: 14:48
 */


use Carbon\Carbon;
use yii\bootstrap\Html;
use yii\web\JqueryAsset;
use yii\web\View;

/** @var View $this */

$this->title = Yii::$app->name;
$dateStr = Carbon::now()->isoFormat('%A %d %B %Y');

/** @noinspection PhpUnhandledExceptionInspection */
$this->registerJsFile('@web/js/path-windows.js', ['depends' => [JqueryAsset::class]]);

?>

<div class="panel panel-default" id="homepage">
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
                <label for="exampleInputEmail1">Copier le path ici</label>
                <?= Html::textInput('path-windows', null, ['class' => "form-control", 'id' => 'path-windows']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 form-group">
                <button class="btn btn-default" id="path-windows-submit">Envoyer</button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 affichage-resultat">
                <?= Html::textInput('path-windows-resultat', null, ['class' => "form-control", 'id' => 'path-windows-resultat', 'disabled' => true]) ?>
            </div>
        </div>
    </div>

    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-12">
                <?= $dateStr ?>
            </div>
        </div>
    </div>
</div>


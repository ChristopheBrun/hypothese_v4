<?php

use app\modules\hlib\HLib;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $name string Nom de l'erreur
 * @var $message string Message d'erreur
 * @var $exception Exception Exception interceptée
 */

$this->title = "Non...";
$this->params['breadcrumbs'][] = $this->title;
$imgUrl = Url::base(true) . '/images/joke.jpg';

?>

<div class="panel panel-default" id="joke-page">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12">
                <h1><?= $this->title ?></h1>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-sm-5">
                <?= Html::img($imgUrl, ['alt' => 'server joke']) ?>
            </div>
            <div class="col-sm-7">
                <p>
                    ...je plaisantais.
                </p>
            </div>
        </div>
    </div>

    <?= $this->render('/site/_inner-footer') ?>

</div>

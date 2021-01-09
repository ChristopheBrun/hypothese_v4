<?php

use yii\bootstrap\Html;

/** @var string $env */

$this->title = "Configuration";
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="panel panel-default" id="homepage">
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
                    <li><?= Html::a('phpinfo', ['phpinfo'], ['target' => 'blank']) ?></li>
                </ul>

                <h2>App config</h2>
                à faire...
                <?php // @todo_cbn ?>
            </div>
        </div>
    </div>

    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-12">
                Environnement : <?= $env ?>
            </div>
        </div>
    </div>
</div>

<?php

/** @var string title */
/** @var string $env */

$this->title = Yii::$app->name;

?>

<div class="panel panel-default" id="homepage">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12">
                <h1>Configuration</h1>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <h2>App config</h2>
            </div>
            <div class="col-sm-12">
                à faire...
                <?php // @todo_cbn ?>
            </div>
        </div>

        <hr/>

        <div class="row">
            <div class="row">
                <div class="col-sm-12">
                    <?= phpinfo() ?>
                </div>
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

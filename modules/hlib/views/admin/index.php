<?php
/**
 * Page d'accueil pour l'administrateur
 */
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\GridListHeader;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var string       $mySqlVersion
 */

$this->title = HLib::t('labels', 'Site information');

?>
<div class="row panel panel-default">

    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <ul class="list-group">
            <?= /** @noinspection PhpUnhandledExceptionInspection */
            GridListHeader::widget(['columns' => [
                ["width" => 6, "label" => HLib::t('labels', 'Name')],
                ["width" => 6, "label" => HLib::t('labels', 'Value')],
            ]]) ?>

            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-6">INTL_ICU_VERSION</div>
                    <div class="col-sm-6"><?= INTL_ICU_VERSION ?> </div>
                </div>
            </li>

            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-6">PHP_VERSION</div>
                    <div class="col-sm-6"><?= PHP_VERSION ?> </div>
                </div>
            </li>

            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-6">MySQL - version</div>
                    <div class="col-sm-6"><?= $mySqlVersion ?> </div>
                </div>
            </li>

            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-6">mb_internal_encoding()</div>
                    <div class="col-sm-6"><?= mb_internal_encoding() ?> </div>
                </div>
            </li>

        </ul>

    </div>

    <div class="panel-footer">

    </div>

</div>

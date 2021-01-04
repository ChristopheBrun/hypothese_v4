<?php

use app\modules\hlib\HLib;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Page spécifique pour les erreurs 404
 *
 * @var $this yii\web\View
 * @var $name string Nom de l'erreur
 * @var $message string Message d'erreur
 * @var $exception Exception Exception interceptée
 */

$this->title = HLib::t('labels', 'Unknown page');
$this->params['breadcrumbs'][] = $this->title;

$imgUrl = Url::base(true) . '/images/error_404.png';
?>
<div class=row">
    <div class="col-sm-12 alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <div class="col-sm-6">
        <?= Html::img($imgUrl, ['alt' => 'unknown page']) ?>
    </div>
    <div class="col-sm-6">
        <p>En fait, cette page n'existe pas...</p>
    </div>
</div>

<div class="panel panel-default" id="error-page">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12">
                <h1><?= $this->title ?></h1>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12 alert alert-danger">
                <?= nl2br(Html::encode($message)) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-5">
                <?= Html::img($imgUrl, ['alt' => 'unknown page']) ?>
            </div>
            <div class="col-sm-7">
                <p>En fait, cette page n'existe pas...</p>
            </div>
        </div>
    </div>

    <?= $this->render('/site/_inner-footer') ?>

</div>


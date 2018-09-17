<?php

use app\modules\hlib\HLib;
use yii\helpers\Html;

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

$imgUrl = '/' . Yii::$app->params['images']['webDirectory'] . '/404.jpg';
?>
<div class=row">
    <div class="col-sm-12 alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <div class="col-sm-5">
        <?= Html::img($imgUrl, ['alt' => 'unknown page']) ?>
    </div>
    <div class="col-sm-7">
        <p>By the way, this page does not exist...</p>
    </div>
</div>

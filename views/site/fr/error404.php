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

$imgUrl = '/' . Yii::$app->params['images']['webDirectory'] . '/error_404.jpg';
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

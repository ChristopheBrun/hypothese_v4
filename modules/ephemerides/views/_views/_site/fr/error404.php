<?php

use yii\helpers\Html;

/**
 * Page spécifique pour les erreurs 404
 *
 * @var $this yii\web\View
 * @var $name string Nom de l'erreur
 * @var $message string Message d'erreur
 * @var $exception Exception Exception interceptée
 */

//$this->title = HLib::t('labels', 'Error'). ' #404';
$this->title = $name;
$imgUrl = '/' . Yii::$app->params['images']['webDirectory'] . '/404.jpg';
?>
<div class="site-error">

    <h1>Page introuvable (#404)</h1>

    <div class="alert alert-warning">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        En fait, cette page n'existe pas...
    </p>

    <p>
        <?= Html::img($imgUrl, ['alt' => 'erreur 404']) ?>
    </p>

</div>

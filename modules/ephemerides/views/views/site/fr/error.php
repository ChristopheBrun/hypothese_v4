<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $name string Nom de l'erreur
 * @var $message string Message d'erreur
 * @var $exception Exception Exception interceptée
 */

$this->title = $name;
$imgUrl = '/' . Yii::$app->params['images']['webDirectory'] . '/error_500.png';
?>
<div class="site-error">

    <h1><?= \app\modules\hlib\HLib::t('labels', 'Server error') ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        <?= Html::img($imgUrl, ['alt' => 'erreur serveur']) ?>
    </p>

    <p>
        Vous ne pouvez pas accéder à cette page tant que l'erreur n'aura pas été analysée par l'administrateur du site.
    </p>

    <p>
        Ré-essayez d'accéder à cette page dans quelques heures.
    </p>

    <p>
        Si le problème persiste, merci de nous <?= Html::a('contacter', Url::to(['/site/contact'], true)) ?>.
    </p>

</div>

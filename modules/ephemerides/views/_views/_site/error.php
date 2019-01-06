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
        <?= Html::img($imgUrl, ['alt' => 'server error']) ?>
    </p>

    <p>
        You won't be able to display this page until the site administrator checks and fixes this error.
    </p>

    <p>
        Try again in a few hours.
    </p>

    <p>
        If the problem persists, please <?= Html::a('contact us', Url::to(['/site/contact'], true)) ?>.
    </p>

</div>

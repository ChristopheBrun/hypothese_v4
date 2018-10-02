<?php

/**
 * @var $this yii\web\View
 */

use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = Yii::$app->name;
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Bienvenue !</h1>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>vous vous trouvez...</h2>
            </div>
            <div class="col-lg-4 text-center">
                <?= Html::img(Url::base(true) . '/images/marmotte.jpg', ['alt' => 'home page']) ?>
            </div>
            <div class="col-lg-4">
                <h3>... sur notre page d'accueil.</h3>
            </div>
        </div>

    </div>
</div>

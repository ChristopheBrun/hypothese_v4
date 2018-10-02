<?php

use app\modules\hlib\HLib;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $name string Nom de l'erreur
 * @var $message string Message d'erreur
 * @var $exception Exception Exception interceptée
 */

$this->title = HLib::t('labels', 'Server error');
$this->params['breadcrumbs'][] = $this->title;

$imgUrl = Url::base(true) . '/images/error_500.png';
?>
<div class=row">
    <div class="col-sm-12 alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <div class="col-sm-5">
        <?= Html::img($imgUrl, ['alt' => 'server error']) ?>
    </div>
    <div class="col-sm-7">
        <p>
            You won't bbe able to access this page until the problemis fixed by the administrator.
        </p>
        <p>
            Please try again in a few hours.
        </p>
        <p>
            If the problem remains, please <?= Html::a(Yii::t('messages', "contact us"), Url::to(['/site/contact'], true)) ?>.
        </p>
    </div>
</div>

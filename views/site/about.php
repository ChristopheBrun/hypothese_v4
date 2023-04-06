<?php

use app\modules\hlib\HLib;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 */

$this->title = HLib::t('labels', 'About');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Vous êtes sur la page "Présentation". Ca pourrait être pire.
    </p>
</div>

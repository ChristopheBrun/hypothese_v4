<?php

/**
 * @var $this yii\web\View
 */

use yii\helpers\Html;

$this->title = \app\modules\hlib\HLib::t('labels', 'About');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Vous êtes sur la page "Présentation". Ca pourrait être pire.
    </p>

    <code><?= __FILE__ ?></code>
</div>

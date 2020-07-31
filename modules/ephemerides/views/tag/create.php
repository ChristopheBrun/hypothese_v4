<?php

use app\modules\ephemerides\EphemeridesModule;
use app\modules\hlib\HLib;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\ephemerides\models\Tag */

$this->title = EphemeridesModule::t('labels', 'Create Tag');
$this->params['breadcrumbs'][] = ['label' => HLib::t('labels', 'Management'), 'url' => ['/site/management']];
$this->params['breadcrumbs'][] = ['label' => EphemeridesModule::t('labels', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

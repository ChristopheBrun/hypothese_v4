<?php

use app\modules\user\UserModule;
use yii\helpers\Html;
use app\modules\hlib\HLib;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = UserModule::t('labels', 'Update {modelClass}: ', [
    'modelClass' => 'User',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => HLib::t('labels', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('labels', 'Update');
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

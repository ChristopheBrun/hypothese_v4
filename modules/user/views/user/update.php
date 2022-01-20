<?php

use app\modules\user\UserModule;
use yii\helpers\Html;
use app\modules\hlib\HLib;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\form\UpdateUserForm */

$this->title = UserModule::t('labels', 'Update User');
$this->params['breadcrumbs'][] = ['label' => UserModule::t('labels', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user->id, 'url' => ['view', 'id' => $model->user->id]];
$this->params['breadcrumbs'][] = HLib::t('labels', 'Update');
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

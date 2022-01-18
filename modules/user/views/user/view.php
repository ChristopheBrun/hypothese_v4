<?php

use app\modules\hlib\helpers\AssetsHelper;
use app\modules\hlib\HLib;
use app\modules\user\UserModule;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => HLib::t('labels', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(HLib::t('labels', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(HLib::t('labels', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => HLib::t('labels', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= /** @noinspection PhpUnhandledExceptionInspection */
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'email:email',
            'confirmation_sent_at',
            'confirmed_at',
            'unconfirmed_email:email',
            'recovery_sent_at',
            'blocked_at',
            'registered_from',
            'logged_in_from',
            'logged_in_at',
            AssetsHelper::detailViewSeparator(),
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

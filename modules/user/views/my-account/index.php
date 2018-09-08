<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\user\UserModule;
use app\modules\hlib\HLib;

/**
 * @var $this yii\web\View
 * @var $model app\modules\user\models\User
 */

$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => UserModule::t('labels', 'My Account'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(HLib::t('labels', 'Update'), ['update-user'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(UserModule::t('labels', 'Request new password'), ['/new-password'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= /** @noinspection PhpUnhandledExceptionInspection */
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'email:email',
            'logged_in_from',
            'logged_in_at',
            'password_updated_at',
            'password_usage',
            'blocked_at',
            'registered_from',
            'confirmed_at',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

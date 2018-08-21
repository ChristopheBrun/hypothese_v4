<?php

use app\modules\user\models\Profile;
use app\modules\ia\widgets\DisplayModelsWithLinks;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\user\UserModule;
use app\modules\ia\IAModule as IA;

/**
 * @var $this yii\web\View
 * @var $model app\modules\user\models\User
 */

$this->title = $model->formatName();
$this->params['breadcrumbs'][] = ['label' => UserModule::t('labels', 'My Profile'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(IA::t('labels', 'Update'), ['update-profile'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= /** @noinspection PhpUnhandledExceptionInspection */
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => UserModule::t('labels', 'My Account'),
                'value' => DisplayModelsWithLinks::widget([
                    'models' => $model,
                    'labelCallback' => function (Profile $model) {
                        return $model->user->email;
                    },
                    'controllerRoute' => '/user/my-account',
                    'controllerView' => 'index',
                    'allowUrlQuery' => false,
                ]),
                'format' => 'html',
            ],
            'first_name',
            'last_name',
            'cellphone',
            'landline_phone',
            'fax',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

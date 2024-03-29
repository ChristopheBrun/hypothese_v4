<?php

/**
 * Liste des utilisateurs avec GridView pour la gestion
 */

/** @var $this yii\web\View */
/** @var $searchModel app\modules\user\models\search\UserSearch */

/** @var $dataProvider yii\data\ActiveDataProvider */

use app\modules\hlib\widgets\DisplayModels;
use app\modules\hlib\widgets\DisplayModelsWithLinks;
use app\modules\user\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\user\UserModule;

$this->title = UserModule::t('labels', 'Users');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(UserModule::t('labels', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?= /** @noinspection PhpUnhandledExceptionInspection */
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'email:email',
            [
                'attribute' => 'profileName',
                'label' => UserModule::t('labels', 'Profile'),
                'value' => function (User $model) {
                    return DisplayModelsWithLinks::widget([
                        'models' => $model->profile,
                        'labelMethod' => 'formatName',
                        'controllerRoute' => '/user/profile',
                    ]);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'authorizationName',
                'label' => UserModule::t('labels', 'Authorizations'),
                'value' => function (User $model) {
                    return DisplayModels::widget([
                        'models' => $model->authorizations,
                        'labelField' => 'item_name',
                    ]);
                },
                'format' => 'html',
            ],
            [
                'class' => 'yii\grid\ActionColumn'
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<?php

/**
 * Barre de menu principale
 */

use app\modules\hlib\HLib;
use app\modules\user\lib\enums\AppRole;
use app\modules\user\UserModule;
use kartik\nav\NavX;
use yii\bootstrap\Html;
use yii\bootstrap\NavBar;
use yii\helpers\Url;

$imgUrl = Url::base(true) . '/images/hypothese-32.png';

NavBar::begin([
    'brandLabel' => Html::img($imgUrl, ['class' => 'main-menu-logo']) . Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);

/** @var app\modules\user\models\User $identity */
$identity = Yii::$app->user->identity;

/** @noinspection PhpUnhandledExceptionInspection */
echo NavX::widget([
    'options' => [
        'class' => 'navbar-nav navbar-right',
    ],
    'items' => [
        [
            'label' => "Accueil",
            'url' => ['/site/index'],
        ],
        [
            'label' => "Des trucs qui traînent",
            'items' => [
                ['label' => "Path windows > linux", 'url' => ['/utilitaires/path-windows']],
                ['label' => "Lettres et le temps", 'url' => ['/lettres'], 'visible' => Yii::$app->user->can(AppRole::SUPERADMIN),],
            ],
        ],
        [
            'label' => HLib::t('labels', "Management"),
            'url' => ['/site/management'],
            'visible' => Yii::$app->user->can('superadmin'),
        ],
        [
            'label' => 'Contact',
            'url' => ['/site/contact'],
        ],
        //
        Yii::$app->user->isGuest ?
            ['label' => UserModule::t('labels', 'Login'), 'url' => ['/user/security/login']]
            :
            '<li>' . Html::beginForm(['/user/security/logout'], 'post')
            . Html::submitButton(UserModule::t('labels', "Logout({email})", ['email' => $identity->email]), ['class' => 'btn btn-link logout'])
            . Html::endForm() . '</li>'
    ],
]);

NavBar::end();

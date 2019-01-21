<?php
/**
 * Barre de menu du site
 * Ref : http://getbootstrap.com/components/#navbar-default
 * todo_cbn Mettre en place un composant comme https://github.com/GrahamCampbell/Laravel-Navigation
 */

use app\modules\cms\HCms;
use app\modules\cms\widgets\FrontendPagesMenuItems;
use app\modules\hlib\helpers\h;
use app\modules\hlib\HLib;
use app\modules\users\hUsers;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \app\modules\users\WebUser $webUser */
$webUser = Yii::$app->user;
?>

<div class="row">
    <nav class="navbar navbar-inverse">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= Url::home(true); ?>">
                <?= Html::img('/images/calendrier-32.png', ['alt' => 'Ephémérides', 'id' => 'brand-image']); ?>
                <?= Yii::$app->params['siteTitle'] ?>
            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <?= /** @noinspection PhpUnhandledExceptionInspection */
            FrontendPagesMenuItems::widget() ?>

            <?php if ($webUser->isAdmin()) : ?>
                <ul class="nav navbar-nav">

                    <?php // Gestion des éphémérides ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <?= Yii::t('labels', 'Manage calendar entries') ?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><?= Html::a(Yii::t('labels', 'Calendar entries'), Url::to(['/calendar-entries/index', 'page' => 1], true)) ?></li>
                            <li><?= Html::a(Yii::t('labels', 'Tags'), Url::to(['/tags/index', 'page' => 1], true)) ?></li>
                        </ul>
                    </li>

                    <?php // Gestion des utilisateurs ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <?= HLib::t('labels', 'Management') ?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><?= Html::a(HLib::t('labels', 'Management'), Url::to(['/hlib/admin/index'])) ?></li>
                            <li><?= Html::a(HLib::t('labels', 'phpinfo'), Url::to(['/hlib/admin/phpinfo'])) ?></li>
                            <li class="divider"></li>
                            <li class="dropdown-header"><?= HLib::t('labels', 'Manage Users') ?></li>
                            <li><?= Html::a(hUsers::t('labels', 'Roles'), Url::to(['/users/roles/index'], true)) ?></li>
                            <?php if ($webUser->isSuperAdmin()) : ?>
                                <li><?= Html::a(hUsers::t('labels', 'Users'), Url::to(['/users/users/index'], true)) ?></li>
                            <?php endif ?>
                        </ul>
                    </li>

                    <?php // Gestion du CMS ?>
                    <?php if ($webUser->isSuperAdmin()) : ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <?= HCms::t('labels', 'CMS') ?> <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><?= Html::a(HCms::t('labels', 'Pages'), Url::to(['/cms/web-pages/index'], true)) ?></li>
                                <li><?= Html::a(HCms::t('labels', 'Web texts'), Url::to(['/cms/web-texts/index'], true)) ?></li>
                                <li><?= Html::a(HCms::t('labels', 'Languages'), Url::to(['/cms/languages/index'], true)) ?></li>
                            </ul>
                        </li>
                    <?php endif ?>

                </ul>
            <?php endif ?>

            <ul class="nav navbar-nav navbar-right">
                <?php // Authentification ?>
                <?php if ($webUser->isGuest()) : ?>
                    <?php if (Yii::$app->params['allowRegistration']) : ?>
                        <li><?= Html::a(hUsers::t('labels', 'Register'), Url::to(['/users/auth/register'], h::protocol())) ?></li>
                    <?php endif ?>
                    <li><?= Html::a(hUsers::t('labels', 'Login'), Url::to(['/users/auth/login'], h::protocol())) ?></li>
                <?php else : ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <?= /** @noinspection PhpUndefinedFieldInspection */
                            $webUser->identity->name; ?><span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><?= Html::a(hUsers::t('labels', 'Logout'), Url::to(['/users/auth/logout'], true)) ?></li>
                        </ul>
                    </li>
                <?php endif ?>
            </ul>

        </div>
    </nav>
</div>
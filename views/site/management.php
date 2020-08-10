<?php

/**
 * @var $this yii\web\View
 */

use app\modules\ephemerides\EphemeridesModule;
use app\modules\hlib\HLib;
use app\modules\user\UserModule;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = Yii::$app->name . ': superadmin';
?>
<div class="row">
    <div class="panel panel-default" id="homepage">
        <div class="panel-heading">
            <h1>Administration</h1>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <h2><?= HLib::t('labels', 'Management') ?></h2>
                    <ol>
                        <li>
                            <?= Html::a("Commandes", Url::to('/site/commands')) ?>
                        </li>
                    </ol>

                    <h2><?= UserModule::t('labels', 'Users') ?></h2>
                    <ol>
                        <li>
                            <?= Html::a(UserModule::t('labels', 'Users'), Url::to('/user/user')) ?>
                        </li>
                        <li>
                            <?= Html::a(UserModule::t('labels', 'Roles'), Url::to('/user/role')) ?>
                        </li>
                        <li>
                            <?= Html::a(UserModule::t('labels', 'Permissions'), Url::to('/user/permission')) ?>
                        </li>
                    </ol>

                    <h2><?= EphemeridesModule::t('labels', 'Calendar Entries') ?></h2>
                    <ol>
                        <li>
                            <?= Html::a(EphemeridesModule::t('labels', 'Calendar Entries'), Url::to('/ephemerides/calendar-entry')) ?>
                        </li>
                        <li>
                            <?= Html::a(EphemeridesModule::t('labels', 'Catégories'), Url::to('/ephemerides/tag')) ?>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-12">

                </div>
            </div>
        </div>
    </div>
</div>


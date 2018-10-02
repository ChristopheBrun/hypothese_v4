<?php

/**
 * @var $this yii\web\View
 */

use app\modules\user\UserModule;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = Yii::$app->name . ': superadmin';
?>
<div class="site-index-superadmin">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-12">
                <h1>Administration</h1>
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
            </div>
        </div>

    </div>
</div>

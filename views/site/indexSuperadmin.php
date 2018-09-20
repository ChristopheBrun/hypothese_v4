<?php

/**
 * @var $this yii\web\View
 */

use app\modules\user\UserModule;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = 'My Yii Application';
?>
<div class="site-index-superadmin">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-12">
                <ul>
                    <li>
                        <?= Html::a(UserModule::t('labels', 'Users'), Url::to('/user/user')) ?>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>

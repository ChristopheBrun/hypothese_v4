<?php

use app\modules\cms\models\WebPage;
use yii\bootstrap\Html;
use yii\helpers\Url;

/**
 * @var WebPage $model
 */

if(!is_null($model->base->menu_index)) {
    $upLink = Html::a(
        Html::img($this->assetManager->getBundle('app\modules\cms\assets\CmsAsset')->baseUrl . '/fleche-monter-verte-32.png'),
        Url::to(['/cms/base-pages/increase-menu-index/', 'id' => $model->base_id])
    );
    $downLink = Html::a(
        Html::img($this->assetManager->getBundle('app\modules\cms\assets\CmsAsset')->baseUrl . '/fleche-descendre-verte-32.png'),
        Url::to(['/cms/base-pages/decrease-menu-index/', 'id' => $model->base_id])
    );

    echo "$upLink $downLink [{$model->base->menu_index}] {$model->menu_title}";
}
else {
    echo '';
}



<?php

namespace app\modules\cms\assets;

use yii\web\AssetBundle;


/**
 * Class CmsAsset
 * @package app\modules\cms\assets
 */
class CmsAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/cms/assets/cms';

    public $css = [
        'cms.css',
    ];

    public $js = [
        'cms.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\modules\hlib\assets\Select2Asset',
        'app\modules\hlib\assets\HLibAsset',
    ];

}
<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace app\modules\ephemerides\assets;

use yii\web\AssetBundle;

/**
 *
 */
class EphemeridesAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/ephemerides/assets';

    public $css = [
        'ephemerides.css',
    ];

    public $js = [
        'ephemerides.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}

<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\hlib\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 *
 * NB : pour le moment on inclut directement des fichiers en les copiant dans @web/css et @web/js sinon il y a des problèmes de rendu...
 * todo_cbn Ne pas oublier de recopier ces fichiers dans le répetoire public correspondant
 * todo_cbn Ne pas oublier de reporter ici les éventuelles modifications faites dans les fichiers copiés sur le répertoire public
 */
class HLibAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/hlib/assets/hlib';

    public $css = [
        'hLib.css',
    ];

    public $js = [
        'hLib.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}

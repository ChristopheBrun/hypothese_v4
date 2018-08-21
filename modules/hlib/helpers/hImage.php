<?php

namespace app\modules\hlib\helpers;
use Intervention\Image\ImageManagerStatic;
use Yii;


/**
 * Class hImage
 * @package app\modules\hlib\helpers
 */
class hImage extends ImageManagerStatic
{
    /**
     * @inheritdoc
     */
    public static function configure(array $config = array())
    {
        $config['driver'] =  Yii::$app->params['images']['driver'];
        return parent::configure($config);
    }
}
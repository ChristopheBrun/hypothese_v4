<?php

namespace app\modules\hlib\helpers;

use app\modules\hlib\HLib;
use Yii;
use yii\helpers\Html;


/**
 * Class hAssets
 * @package app\modules\hlib\helpers
 * @deprecated Utiliser AssetsHelper
 */
class hAssets
{

    /**
     * @param bool $bool
     * @param bool $showFalse
     * @return string
     */
    public static function getImageTagForBoolean($bool, $showFalse = true)
    {
        $out = "";
        if ($bool) {
            $out = Html::img(Yii::$app->homeUrl . 'images/ok-16.png', ['alt' => HLib::t('labels', 'yes')]);
        }
        else {
            if ($showFalse) {
                $out = Html::img(Yii::$app->homeUrl . 'images/ko-16.png', ['alt' => HLib::t('labels', 'no')]);
            }
        }

        return $out;
    }

}
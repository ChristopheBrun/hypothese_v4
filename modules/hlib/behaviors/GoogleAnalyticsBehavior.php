<?php

namespace app\modules\hlib\behaviors;

use yii\base\ActionFilter;


/**
 * Class GoogleAnalyticsBehavior
 * Active le script pour Google Analytics.
 * Le contrôleur qui utilise ce behavior doit posséder un attribut $enableGoogleAnalytics réglé à false par défaut
 *
 * @package app\modules\cms\helpers
 */
class GoogleAnalyticsBehavior extends ActionFilter
{

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        if(YII_ENV_PROD) {
            /** @noinspection PhpUndefinedFieldInspection */
            $action->controller->enableGoogleAnalytics = true;
        }

        return true;
    }

}
<?php

namespace app\modules\hlib;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

/**
 * Class HLib
 *
 * @package app\modules\users
 */
class HLib extends Module implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\hlib\controllers';

    /**
     *
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * Ajout du routing spécifique au module
     *      format : [verbe] regex-url => route
     *
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            // backend
            'GET    hlib/admin' => 'hlib/admin/index',
        ], false);
    }

    /**
     *
     */
    public static function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/hlib/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/modules/hlib/messages',
            'fileMap' => [
                'modules/hlib/labels' => 'app.php',
                'modules/hlib/messages' => 'messages.php',
                'modules/hlib/titles' => 'titles.php',
            ],
        ];
    }

    /**
     * @param       $category
     * @param       $message
     * @param array $params
     * @param null $language
     * @return mixed
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/hlib/' . $category, $message, $params, $language);
    }
}

<?php
namespace app\modules\cms;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

/**
 * Class HCms
 * @package app\modules\cms
 */
class HCms extends Module implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\cms\controllers';

    /**
     * @var string $frontendLayout Le layout à utiliser pour les pages du frontend.
     * On utilise le layout par défaut si $frontendLayout n'est pas renseigné
     */
    public $frontendLayout;

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
        $app->getUrlManager()->addRules(include(__DIR__ . '/config/routing.php'), false);
    }

    /**
     *
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/cms/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/modules/cms/messages',
            'fileMap' => [
                'modules/cms/labels' => 'app.php',
                'modules/cms/messages' => 'messages.php',
                'modules/cms/titles' => 'titles.php',
            ],
        ];
    }

    /**
     * @param       $category
     * @param       $message
     * @param array $params
     * @param null  $language
     * @return mixed
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/cms/' . $category, $message, $params, $language);
    }

}

<?php

namespace app\modules\ephemerides;

use Yii;

/**
 * ephemerides module definition class
 */
class EphemeridesModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ephemerides\controllers';

    /** @var array  */
    public $images = [
        'webDirectory' => 'calendar_entries',
        'extension' => 'jpg',
        'originalSize' => [
            'width' => 600, 'height' => 0
        ],
        'thumbnailsSizes' => [
            'std' => ['width' => 200, 'height' => 0],
            'lg' => ['width' => 400, 'height' => 0],
            'xs' => ['width' => 50, 'height' => 0],
        ],
    ];

    /** @var string  */
    public $sortSessionKey = 'sort_calendar_entries';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        static::registerTranslations();

//        // Lancement du gestionnaire d'événements (singleton)
//        Yii::createObject('user/UserEventHandler');
    }

    /**
     * Déclaration des ressources pour les chaines et leur traduction
     */
    public static function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/user/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/modules/user/messages',
            'fileMap' => [
                'modules/user/labels' => 'labels.php',
                'modules/user/messages' => 'messages.php',
            ],
        ];
    }

    /**
     * Raccourci pour la fonction de traduction
     *
     * @param string $category
     * @param string $message
     * @param array $params
     * @param string $language
     * @return mixed
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ephemerides/' . $category, $message, $params, $language);
    }

}

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

    /** @var array */
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

    /** @var string */
    public $sortSessionKey = 'sort_calendar_entries';

    /**
     *
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     *
     */
    public static function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ephemerides/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/modules/ephemerides/messages',
            'fileMap' => [
                'modules/ephemerides/labels' => 'labels.php',
                'modules/ephemerides/messages' => 'messages.php',
                'modules/ephemerides/titles' => 'titles.php',
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
        return Yii::t('modules/ephemerides/' . $category, $message, $params, $language);
    }

}

<?php

namespace app\modules\ephemerides;


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

}

<?php

namespace app\modules\hlib\widgets;

use Yii;
use yii\bootstrap\Widget;


/**
 * Class FlashMessages
 * @package app\modules\hlib\widgets
 */
class FlashMessages extends Widget
{

    /** @var array $levels Niveaux des messages flash, qui correspondent aussi aux classes bootstrap pour l'affichage */
    public $levels = ['success', 'warning', 'danger'];

    /**
     * @return string
     */
    public function run()
    {
        $messages = [];
        foreach ($this->levels as $level) {
            $key = 'flash-' . $level;
            $flashes = Yii::$app->session->getFlash($key);

            if ($flashes && !is_array($flashes)) {
                $tmp = $flashes;
                $flashes = [$tmp];
            }

            if (count($flashes)) {
                $messages[$level] = $flashes;
            }
        }

        if (!count($messages)) {
            return '';
        }

        return $this->render('flashMessages', [
            'messages' => $messages,
        ]);
    }

}
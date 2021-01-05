<?php /** @noinspection PhpUnused */

namespace app\controllers;

use yii\web\Controller;
use yii\web\ViewAction;

/**
 * Class MemosController
 * @package app\controllers
 */
class MemosController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'index' => [
                'class' => ViewAction::class,
            ],
            'console-windows' => [
                'class' => ViewAction::class,
                'defaultView' => 'console-windows',
            ],
            'config-php' => [
                'class' => ViewAction::class,
                'defaultView' => 'config-php',
            ],
        ];
    }

}
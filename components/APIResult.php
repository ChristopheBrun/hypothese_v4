<?php


namespace app\components;

use yii\base\Component;

/**
 * Class APIResult
 * @package app\lib
 */
class APIResult extends Component
{
    const ERRCODE_OK = 0;

    public $errCode = self::ERRCODE_OK;
    public $messages = [];
    public $data = [];
}
<?php

/**
 * Created by PhpStorm.
 * User: Christophe
 * Date: 21/11/2018
 * Time: 11:18
 */

namespace app\models;

use yii\base\Model;

/**
 * Class TestModel
 * @package app\models
 *
 * Sert à faire quelques tests de code sur une instance de Model
 */
class TestModel extends Model
{
    private $code;

    /*
     *
     */
    public function getCode()
    {
        return $this->code;
    }

}
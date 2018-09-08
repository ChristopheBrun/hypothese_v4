<?php

namespace app\modules\hlib\validators;

use app\modules\hlib\HLib;
use yii\validators\Validator;


/**
 * Class FaxValidator
 * @package app\modules\ia\validators
 * @see http://www.yiiframework.com/doc-2.0/guide-input-validation.html#standalone-validators
 */
class FaxValidator extends Validator
{
    /**
     * Vérifie que le numéro saisi respecte les règles suivantes :
     *      - commence par 06 ou 07
     *      - contient 10 chiffres
     *
     * @param \yii\base\Model $model
     * @param string          $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $num = $model->$attribute;

        $num = trim($num);
        $num = str_replace(" ", "", $num);
        $num = str_replace(",", "", $num);
        $num = str_replace("-", "", $num);
        $num = str_replace(".", "", $num);

        if (!preg_match('/^0[1-5]\d{8}$/', $num)) {
            $this->addError($model, $attribute, HLib::t('messages', "Invalid fax number"));
        }
    }
}

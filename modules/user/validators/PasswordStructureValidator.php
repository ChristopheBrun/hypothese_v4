<?php

namespace app\modules\user\validators;

use Stringy\Stringy;
use yii\validators\Validator;

use app\modules\user\UserModule;


/**
 * Class PasswordStructureValidator
 * @package app\modules\ia\validators
 * @see http://www.yiiframework.com/doc-2.0/guide-input-validation.html#standalone-validators
 */
class PasswordStructureValidator extends Validator
{
    /**
     * Vérifie que le mot de passe respecte les règles suivantes :
     *      - au moins une majuscule
     *      - au moins une minuscule
     *      - au moins un chiffre
     * @param \yii\base\Model $model
     * @param string          $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $pwd = new Stringy($model->$attribute);
        $lg = $pwd->count();

        $errors = [];

        $test = $pwd->regexReplace('[a-z]+', '');
        if ($test->count() == $lg) {
            $errors[] = UserModule::t('messages', "one lowercase character");
        }

        $test = $pwd->regexReplace('[A-Z]+', '');
        if ($test->count() == $lg) {
            $errors[] = UserModule::t('messages', "one uppercase character");
        }

        $test = $pwd->regexReplace('[0-9]+', '');
        if ($test->count() == $lg) {
            $errors[] = UserModule::t('messages', "one digit");
        }

        if ($errors) {
            $this->addError($model, $attribute, UserModule::t('messages',
                "The password must contain at least {errors}", ['errors' => implode(' + ', $errors)])
            );
        }
    }
}

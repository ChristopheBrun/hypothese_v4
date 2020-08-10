<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ConsoleCommandForm extends Model
{
    public $command;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // required
            [['command'],
                'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'command' => Yii::t('app', 'command'),
        ];
    }

}

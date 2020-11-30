<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body', 'verifyCode'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'yourName'),
            'email' => Yii::t('app', 'yourEmail'),
            'subject' => Yii::t('app', 'subject'),
            'body' => Yii::t('app', 'yourMessage'),
            'verifyCode' => Yii::t('app', 'verifyCode'),
        ];
    }

    /**
     * Envoie le mail à l'administrateur.
     *
     * @return bool
     */
    public function sendMail()
    {
        $infos = sprintf("Mail envoyé par : %s (%s)", $this->email, $this->name);
        return Yii::$app->mailer->compose()
            ->setTo(Yii::$app->params['adminEmail'])
            ->setFrom(Yii::$app->params['fromEmail'])
            ->setSubject($this->subject)
            ->setTextBody($infos . "\n\n" . $this->body)
            ->send();
    }
}

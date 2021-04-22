<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public string $name = '';
    public string $email = '';
    public string $subject = '';
    public string $body = '';
    public string $verifyCode = '';

    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        return [
            // required
            [['name', 'email', 'subject', 'body'],
                'required'],
            [['verifyCode'],
                'required', 'when' => function () {
                return Yii::$app->params['captchaEnabled'];
            }],
            // email
            ['email',
                'email'],
            // captcha
            ['verifyCode',
                'captcha', 'when' => function () {
                return Yii::$app->params['captchaEnabled'];
            }],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels(): array
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
    public function sendMail(): bool
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

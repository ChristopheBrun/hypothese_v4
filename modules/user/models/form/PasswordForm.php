<?php

namespace app\modules\user\models\form;

use app\modules\user\models\User;
use app\modules\user\UserModule;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class PasswordForm
 * @package app\modules\user\models
 */
class PasswordForm extends Model
{
    /** @var string $password */
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => UserModule::getInstance()->passwordMinLength],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => UserModule::t('labels', 'Password'),
        ];
    }

    /**
     * Mise à jour du mot de passe de l'utilisateur
     *
     * @param User $user
     * @return bool
     */
    public function resetPassword(User $user)
    {
        if (!$this->validate()) {
            Yii::debug($this->getErrors());
            return false;
        }

        try {
            $user->scenario = User::SCENARIO_PASSWORD;
            $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            $user->password_updated_at = date('Y:m:d H:i:s');
            $user->password_usage = 0;
            $user->confirmed_at = date('Y-m-d H:i:s');
            if ($user->save()) {
                return true;
            }
        } catch (Exception $x) {
            Yii::error($x);
        }

        return false;
    }
}
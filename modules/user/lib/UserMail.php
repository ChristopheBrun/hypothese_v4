<?php

namespace app\modules\user\lib;

use app\modules\user\models\User;
use Yii;
use yii\base\Component;
use app\modules\user\UserModule;


/**
 * Class UserMail
 * @package app\modules\user\lib
 */
class UserMail extends Component
{
    //
    // Configuration
    //

    /** @var bool */
    public $logErrors = true;

    /** @var string */
    public $mailViews = '@app/modules/user/mails';

    //
    //
    //

    /**
     * Mail avec un lien de confirmation pour l'inscription sur le site
     *
     * @param string $to
     * @param User $user
     * @param string $token
     * @param string $url
     * @return bool
     */
    public function passwordConfirmationLink($to, $user, $token, $url)
    {
        $ok = Yii::$app->mailer->compose($this->mailViews . '/registration', compact('user', 'token', 'url'))
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($to)
            ->setSubject(UserModule::t('messages', "Your confirmation link"))
            ->send();

        if (!$ok) {
            Yii::error([$to, $user, $token, $url]);
        }

        return $ok;
    }

    /**
     * Mail avec un lien permettant de ré-initialiser son mot de passe
     *
     * @param string $to
     * @param User $user
     * @param string $token
     * @param string $url
     * @return bool
     */
    public function passwordResetLink($to, $user, $token, $url)
    {
        $ok = Yii::$app->mailer->compose($this->mailViews . '/resetPassword', compact('user', 'token', 'url'))
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($to)
            ->setSubject(Yii::t('messages', "Your reset password link"))
            ->send();

        if (!$ok) {
            Yii::error([$to, $user, $token, $url]);
        }

        return $ok;
    }

    /**
     * @param string $to
     * @param User $user
     * @param string $token
     * @param string $url
     * @return bool
     */
    public function userAccountCreation($to, $user, $token, $url)
    {
        $ok = Yii::$app->mailer->compose($this->mailViews . '/accountCreation', compact('url'))
            ->setFrom(Yii::$app->params['mails']['fromAdminToUser'])
            ->setTo($to)
            ->setSubject(UserModule::t('messages', "Creation of your user account on {0}", Yii::$app->params['mails']['applicationShortName']))
            ->send();

        if (!$ok) {
            Yii::error([$to, $user, $token, $url]);
        }

        return $ok;
    }

    /**
     * @param string $to
     * @param User $user
     * @param string $token
     * @param string $url
     * @return bool
     */
    public function accountUpdatedByAdmin($to, $user, $token, $url)
    {
        $ok = Yii::$app->mailer->compose($this->mailViews . '/accountUpdatedByAdmin', compact('url'))
            ->setFrom(Yii::$app->params['mails']['fromAdminToUser'])
            ->setTo($to)
            ->setSubject(UserModule::t('messages', "Your user account on {0} has been updated", Yii::$app->params['mails']['applicationShortName']))
            ->send();

        if (!$ok) {
            Yii::error([$to, $user, $token, $url]);
        }

        return $ok;
    }

    /**
     * @param string $to
     * @param User $user
     * @param string $token
     * @param string $url
     * @return bool
     */
    public function accountUpdatedByOwner($to, $user, $token, $url)
    {
        $ok = Yii::$app->mailer->compose($this->mailViews . '/accountUpdatedByOwner', compact('url'))
            ->setFrom(Yii::$app->params['mails']['fromAdminToUser'])
            ->setTo($to)
            ->setSubject(UserModule::t('messages', "You have updated your user account on {0}", Yii::$app->params['mails']['applicationShortName']))
            ->send();

        if (!$ok) {
            Yii::error([$to, $user, $token, $url]);
        }

        return $ok;
    }

}
<?php

namespace app\modules\user\models\form;

use app\modules\user\models\Profile;
use app\modules\user\models\User;
use Exception;
use Yii;
use yii\base\Model;


/**
 * Class RegistrationForm
 * @package app\models
 *
 * Gestion du formulaire d'inscription
 */
class RegistrationForm extends Model
{
    /** @var User */
    public $user = null;

    /** @var Profile */
    public $profile = null;

    /**
     * RegistrationForm constructor.
     * @param array $config
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->user = Yii::createObject(User::class);
        $this->user->setScenario(User::SCENARIO_REGISTER);

        $this->profile = Yii::createObject(Profile::class);
        $this->profile->setScenario(Profile::SCENARIO_REGISTER);
    }

    /**
     * @param array $data
     * @param null  $formName
     * @return bool
     */
    public function load($data, $formName = null)
    {
        return $this->user->load($data, $formName) && $this->profile->load($data, $formName);
    }

    /**
     * @param null $attributeNames
     * @param bool $clearErrors
     * @return bool
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        return $this->user->validate($attributeNames, $clearErrors) & $this->profile->validate($attributeNames, $clearErrors);
        // @internal : on utilise le & logique ici pour bien valider & récupérer les erreurs sur les deux objets
    }

    /**
     * Inscription d'un nouvel utilisateur.
     * Il ne s'agit que de la première étape. La création du mot de passe et la validation du compte ont lieu après
     * @see \app\modules\user\lib\UserEventHandler
     *
     * @return bool
     * @throws \yii\db\Exception
     */
    public function registerUser()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->beforeCreateUser();
            if ($this->user->save()) {
                // Création du profil associé
                $this->beforeCreateProfile();
                if ($this->profile->save() && $this->completeRegistration()) {
                    $transaction->commit();
                    return true;
                }
            }
        } catch (Exception $x) {
            Yii::error($x);
        }

        $transaction->rollBack();
        return false;
    }

    /**
     * Actions supplémentaires à encapsules dans la transaction
     *
     * @return bool
     */
    protected function completeRegistration()
    {
        return true;
    }

    /**
     *
     */
    protected function beforeCreateUser()
    {
        $this->user->registered_from = Yii::$app->request->getUserIP();
        $this->user->password_hash = '';
        $this->user->auth_key = '';
    }

    /**
     *
     */
    protected function beforeCreateProfile()
    {
        $this->profile->user_id = $this->user->id;
    }

}

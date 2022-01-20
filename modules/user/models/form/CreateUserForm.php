<?php

namespace app\modules\user\models\form;

use app\modules\hlib\lib\exceptions\ModelLoadException;
use app\modules\hlib\lib\exceptions\ModelSaveException;
use app\modules\hlib\lib\exceptions\ModelValidationException;
use app\modules\user\models\Profile;
use app\modules\user\models\User;
use app\modules\user\UserModule;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;


/**
 * Class CreateUserForm
 * @package app\models
 */
class CreateUserForm extends Model
{
    public ?User $user = null;
    public ?Profile $profile = null;
    public string $password = '';
    public bool $isNewRecord = true;

    /**
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->user = Yii::createObject(User::class);
        $this->user->setScenario(User::SCENARIO_CREATE);

        $this->profile = Yii::createObject(Profile::class);
        $this->profile->setScenario(Profile::SCENARIO_CREATE);
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'password' => UserModule::t('labels', "Password"),
        ];
    }

    /**
     * @param array $data
     * @param ?string $formName
     * @throws ModelLoadException
     */
    public function load($data, $formName = null): void
    {
        if (!$this->user->load($data, $formName)) {
            throw new ModelLoadException($this->user, $data);
        }

        if (!$this->profile->load($data, $formName)) {
            throw new ModelLoadException($this->profile, $data);
        }
    }

    /**
     * @param ?array $attributeNames
     * @param bool $clearErrors
     * @throws ModelValidationException
     */
    public function validate($attributeNames = null, $clearErrors = true): void
    {
        if (!$this->user->validate($attributeNames, $clearErrors)) {
            throw new ModelValidationException($this->user);
        }

        if (!$this->profile->validate($attributeNames, $clearErrors)) {
            throw new ModelValidationException($this->profile);
        }
    }

    /**
     * @return void
     * @throws ModelSaveException
     */
    public function save(): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$this->user->save()) {
                throw new ModelSaveException($this->user);
            }

            $this->profile->user_id = $this->user->id;
            if (!$this->profile->save()) {
                throw new ModelSaveException($this->profile);
            }
        } catch (Exception $x) {
            $transaction->rollBack();
            Yii::error($x);
            throw $x;
        }
    }

    /**
     * @param array $data
     * @return void
     * @throws ModelLoadException
     * @throws ModelSaveException
     * @throws ModelValidationException
     * @throws \yii\base\Exception
     */
    public function process(array $data): void
    {
        $this->load($data);

        $this->validate();

        $this->user->password_hash = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        $this->user->auth_key = '';
        $this->save();
    }
}

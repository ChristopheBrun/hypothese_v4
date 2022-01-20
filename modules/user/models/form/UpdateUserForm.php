<?php

namespace app\modules\user\models\form;

use app\modules\hlib\lib\exceptions\ModelLoadException;
use app\modules\hlib\lib\exceptions\ModelSaveException;
use app\modules\hlib\lib\exceptions\ModelValidationException;
use app\modules\user\models\Profile;
use app\modules\user\models\User;
use Yii;
use yii\base\InvalidConfigException;


/**
 * Class UpdateUserForm
 * @package app\models
 */
class UpdateUserForm extends CreateUserForm
{
    public bool $isNewRecord = false;

    /**
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->user = $config['user'];
        $this->user->setScenario(User::SCENARIO_UPDATE);

        $this->profile = $this->user->profile ?: $this->createDefaultProfile($this->user->id) ;
        $this->profile->setScenario(Profile::SCENARIO_UPDATE);
    }

    /**
     * @param int $userId
     * @return Profile
     * @throws InvalidConfigException
     */
    private function createDefaultProfile(int $userId): Profile
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Yii::createObject(['class' => Profile::class, 'user_id' => $userId]);
    }

    /**
     * @param array $data
     * @return void
     * @throws ModelLoadException
     * @throws ModelSaveException
     * @throws ModelValidationException
     */
    public function process(array $data): void
    {
        $this->load($data);
        $this->validate();
        $this->save();
    }
}

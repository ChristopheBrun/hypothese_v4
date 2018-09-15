<?php

namespace app\modules\user\helpers;

use app\modules\hlib\helpers\ModelHelper;
use app\modules\user\lib\enums\AppRole;
use app\modules\user\models\Profile;
use app\modules\user\models\User;
use Exception;
use Yii;

/**
 * Class FixturesHelper
 * @package app\modules\user\helpers
 */
class FixturesHelper
{
    private $superadminEmail = 'superadmin@hypothese.net';

    /**
     * @throws \yii\base\Exception
     * @throws Exception
     */
    public function createSuperadmin()
    {
        $data = [
            'scenario' => User::SCENARIO_REGISTER,
            'email' => $this->superadminEmail,
            'password_hash' => '',
            'auth_key' => '',
            'registered_from' => '',
        ];
        $user = new User($data);
        if (!$user->save()) {
            throw new Exception(ModelHelper::errMsg('save', $user));
        }

        $data = [
            'scenario' => Profile::SCENARIO_REGISTER,
            'last_name' => 'Administrateur',
            'first_name' => 'Super',
            'user_id' => $user->id,
        ];
        $profile = new Profile($data);
        if (!$profile->save()) {
            throw new Exception(ModelHelper::errMsg('save', $profile));
        }

        $user->scenario = User::SCENARIO_PASSWORD;
        $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash('ght4519');
        $user->password_updated_at = date('Y:m:d H:i:s');
        $user->password_usage = 0;
        $user->confirmed_at = date('Y-m-d H:i:s');
        if (!$user->save()) {
            throw new Exception(ModelHelper::errMsg('save', $user));
        }
    }

    /**
     *
     */
    public function removeSuperadmin()
    {
        $auth = Yii::$app->getAuthManager();

        $user = User::find()->byEmail($this->superadminEmail)->one();
        $role = $auth->getRole(AppRole::SUPERADMIN);

        if ($user) {
            $auth->revoke($role, $user->id);
        }

        $auth->remove($role);
    }

    /**
     * @throws \Exception
     */
    public function createSuperadminRole()
    {
        $auth = Yii::$app->getAuthManager();
        $role = $auth->createRole(AppRole::SUPERADMIN);
        $role->description = 'Super administrateur';
        $auth->add($role);
    }

    /**
     *
     */
    public function removeSuperadminRole()
    {
        $auth = Yii::$app->getAuthManager();
        $role = $auth->getRole(AppRole::SUPERADMIN);
        $auth->remove($role);
    }

    /**
     * @throws \Exception
     */
    public function createUserManagementPermissions()
    {
        $auth = Yii::$app->getAuthManager();

        $manageUsers = $auth->createPermission('manageUsers');
        $manageUsers->description = 'Gérer les utilisateurs';
        $auth->add($manageUsers);

        $permission = $auth->createPermission('createUser');
        $permission->description = 'Créer un utilisateur';
        $auth->add($permission);
        $auth->addChild($manageUsers, $permission);

        $permission = $auth->createPermission('updateUser');
        $permission->description = 'Modifier un utilisateur';
        $auth->add($permission);
        $auth->addChild($manageUsers, $permission);

        $permission = $auth->createPermission('deleteUser');
        $permission->description = 'Supprimer un utilisateur';
        $auth->add($permission);
        $auth->addChild($manageUsers, $permission);
    }

    /**
     *
     */
    public function removeUserManagementPermissions()
    {
        $auth = Yii::$app->getAuthManager();
        $permission = $auth->getPermission('createUser');
        $auth->remove($permission);
        $permission = $auth->getPermission('updateUser');
        $auth->remove($permission);
        $permission = $auth->getPermission('deleteUser');
        $auth->remove($permission);
        $permission = $auth->getPermission('manageUsers');
        $auth->remove($permission);
    }

    /**
     * @throws \Exception
     */
    public function createPrivilegesManagementPermissions()
    {
        $auth = Yii::$app->getAuthManager();

        $permission = $auth->createPermission('managePrivileges');
        $permission->description = 'Gérer les droits';
        $auth->add($permission);
    }

    /**
     */
    public function removePrivilegesManagementPermissions()
    {
        $auth = Yii::$app->getAuthManager();
        $permission = $auth->getPermission('managePrivileges');
        $auth->remove($permission);
    }

    /**
     * @throws \Exception
     */
    public function setSuperadminPrivileges()
    {
        $auth = Yii::$app->getAuthManager();
        $user = User::find()->byEmail($this->superadminEmail)->one();

        $role = $auth->getRole(AppRole::SUPERADMIN);
        $auth->assign($role, $user->id);

        $permission = $auth->getPermission('manageUsers');
        $auth->addChild($role, $permission);

        $permission = $auth->getPermission('managePrivileges');
        $auth->addChild($role, $permission);
    }

}
<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\user\commands;

use app\modules\user\helpers\FixturesHelper;
use Exception;
use yii\console\Controller;

/**
 *
 */
class FixturesController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionInitUserAndRbac()
    {
        $helper = new FixturesHelper();
        $helper->createSuperadmin();
        $helper->createSuperadminRole();
        $helper->createUserManagementPermissions();
        $helper->createPrivilegesManagementPermissions();
        $helper->setSuperadminPrivileges();
    }

}

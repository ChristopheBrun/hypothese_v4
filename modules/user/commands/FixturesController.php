<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\user\commands;

use app\modules\user\helpers\FixturesHelper;
use Exception;
use Yii;
use yii\console\Controller;

/**
 *
 */
class FixturesController extends Controller
{
    /**
     *
     */
    public function actionInitUserAndRbac()
    {
        echo "Env : " . YII_ENV . "\n";
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $helper = new FixturesHelper();
            $helper->createSuperadmin();
            echo "createSuperadmin\n";
            $helper->createSuperadminRole();
            echo "createSuperadminRole\n";
            $helper->createUserManagementPermissions();
            echo "createUserManagementPermissions\n";
            $helper->createPrivilegesManagementPermissions();
            echo "createPrivilegesManagementPermissions\n";
            $helper->setSuperadminPrivileges();
            echo "setSuperadminPrivileges\n";
            $transaction->commit();
            echo ">> OK !\n";
        } catch (Exception $x) {
            Yii::error($x->getMessage());
            echo $x->getMessage(), "\n";
            try {
                $transaction->rollBack();
            } catch (\yii\db\Exception $e) {
                Yii::error($x->getMessage());
                echo $e->getMessage() . "\n";
            }
        }
    }

}

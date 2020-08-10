<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\modules\user\models\User;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 *
 */
class TestController extends Controller
{
    /**
     * @return string
     */
    public function actionFindUser()
    {
        echo "Env : " . YII_ENV . "\n";
        $time = 1534878269;
        echo date('Y-m-d H:i:s', $time), "\n";

        echo User::find()
            ->where('created_at != updated_at OR password_usage = :count', ['count' => 0])
            ->andWhere('id > 10 OR blocked_at IS NULL')
            ->count();
        echo ">> OK\n";

        return ExitCode::getReason(ExitCode::OK);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionReadonlyModel()
    {
//        $model = new TestModel();
//        echo "code : $model->code\n";
//
//        $model->code = "set code";
//        echo "code : $model->code\n";
//
//        $model->setCode("appel à setCode");
//        echo "code : $model->code\n";
//
        return ExitCode::getReason(ExitCode::OK);
    }

    public function actionTest()
    {
        echo __METHOD__;
    }
}

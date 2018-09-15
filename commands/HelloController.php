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
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }

    /**
     *
     */
    public function actionTest()
    {
        echo "Env : " . YII_ENV . "\n";
        $time = 1534878269;
        echo date('Y-m-d H:i:s', $time), "\n";

        echo User::find()
            ->where('created_at != updated_at OR password_usage = :count', ['count' => 0])
            ->andWhere('id > 10 OR blocked_at IS NULL')
            ->count();
        echo ">> OK\n";
    }
}

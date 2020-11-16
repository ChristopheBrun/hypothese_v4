<?php /** @noinspection PhpIllegalPsrClassPathInspection */

use app\modules\user\models\User;
use yii\db\Migration;

/**
 * Class m201116_103106_addAPIUser
 */
class m201116_103106_addAPIUser extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $user = Yii::createObject(User::class);
        $user->email = 'test-api@hypothese.net';
        $user->auth_key = 'test-api-key';
        $user->password_hash = '';
        $user->confirmed_at = date('Y-m-d H:i:s');
        return $user->save(false);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $user = User::findOne(['email' => 'test-api@hypothese.net']);
        return $user->delete();
    }

}

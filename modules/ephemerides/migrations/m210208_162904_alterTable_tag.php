<?php /** @noinspection PhpIllegalPsrClassPathInspection */

use yii\db\Migration;

/**
 * Class m210208_162904_alterTable_tag
 */
class m210208_162904_alterTable_tag extends Migration
{
    private $tableName = 'tag';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'rank', $this->integer()->after('label'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'rank');
    }

}

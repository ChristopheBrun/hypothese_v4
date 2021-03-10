<?php /** @noinspection PhpIllegalPsrClassPathInspection */

use yii\db\Migration;

/**
 * Class m210310_165316_altertable_calendar_entry
 */
class m210310_165316_altertable_calendar_entry extends Migration
{
    private $tableName ='calendar_entry';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'source_image', $this->string(250)->after('notes')->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'source_image');
    }

}

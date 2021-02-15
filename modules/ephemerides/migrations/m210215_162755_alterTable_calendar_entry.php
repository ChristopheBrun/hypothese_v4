<?php

use yii\db\Migration;

/**
 * Class m210215_162755_alterTable_calendar_entry
 */
class m210215_162755_alterTable_calendar_entry extends Migration
{
    private $tableName = 'calendar_entry';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'twitter', $this->string(250)->after('description')->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'twitter');

    }

}

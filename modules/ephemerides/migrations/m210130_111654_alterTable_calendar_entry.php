<?php

use yii\db\Migration;

/**
 * Class m210130_111654_alterTable_calendar_entry
 */
class m210130_111654_alterTable_calendar_entry extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('calendar_entry', 'domaine');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn($this->tableName, 'domaine', $this->string(50)->after('description'));
    }
}

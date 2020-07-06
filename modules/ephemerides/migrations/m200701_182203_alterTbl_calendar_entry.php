<?php

use yii\db\Migration;

/**
 * Class m200701_182203_alterTbl_calendar_entry
 */
class m200701_182203_alterTbl_calendar_entry extends Migration
{
    private $tableName = 'calendar_entry';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'description', $this->text()->after('title'));
        $this->addColumn($this->tableName, 'domaine', $this->string(50)->after('description'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'description');
        $this->dropColumn($this->tableName, 'domaine');
    }
}

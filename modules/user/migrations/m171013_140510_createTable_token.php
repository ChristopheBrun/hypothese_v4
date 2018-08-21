<?php

use yii\db\Migration;

class m171013_140510_createTable_token extends Migration
{
    private $tableName = 'token';

    /**
     *
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(20),
            'user_id' => $this->integer(20)->notNull(),
            'code' => $this->text()->notNull(),
            'type' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey("fk-$this->tableName-user", $this->tableName, 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     *
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }

}

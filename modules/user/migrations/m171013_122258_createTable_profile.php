<?php

use yii\db\Migration;

class m171013_122258_createTable_profile extends Migration
{
    private $tableName = 'profile';
    private $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    /**
     *
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(20),
            'user_id' => $this->integer(20)->notNull(),
            'first_name' => $this->string(255),
            'last_name' => $this->string(255),
            'cellphone' => $this->string(25),
            'landline_phone' => $this->string(25),
            'fax' => $this->string(25),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ], $this->tableOptions);

        $this->addForeignKey("fk-$this->tableName-user", $this->tableName, 'user_id', 'user', 'id', 'CASCADE');
        $this->createIndex("unique-$this->tableName-user_id", $this->tableName, 'user_id', true);
    }

    /**
     *
     */
    public function safeDown()
    {
        $this->dropTable('profile');
    }
}

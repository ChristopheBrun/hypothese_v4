<?php

use yii\db\Migration;

class m171013_120836_createTable_user extends Migration
{
    private $tableName = 'user';
    private $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    /**
     *
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(20),
            'email' => $this->string(255)->notNull(),
            'logged_in_from' => $this->string(255),
            'logged_in_at' => $this->dateTime(),
            'password_updated_at' => $this->dateTime(),
            'password_usage' => $this->integer(),
            'blocked_at' => $this->dateTime(),
            'registered_from' => $this->integer(),
            'password_hash' => $this->string(60)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'confirmed_at' => $this->dateTime(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $this->tableOptions);

        $this->createIndex("unique-$this->tableName-email", $this->tableName, 'email', true);
    }

    /**
     *
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}

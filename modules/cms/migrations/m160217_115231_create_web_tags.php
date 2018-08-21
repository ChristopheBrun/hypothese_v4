<?php

use yii\db\Migration;

class m160217_115231_create_web_tags extends Migration
{
    public function up()
    {
        $this->createTable('web_tags', [
            'id' => $this->primaryKey(),
            'base_id' => $this->integer()->notNull(),
            'language_id' => $this->integer()->notNull(),
            'label' => $this->string(128)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey('fk-web_tags-base_tags', 'web_tags', 'base_id', 'base_tags', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk-web_tags-languages', 'web_tags', 'language_id', 'languages', 'id', 'restrict', 'cascade');
    }

    public function down()
    {
        $this->dropTable('web_tags');
    }
}

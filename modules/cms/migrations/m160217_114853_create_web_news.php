<?php

use yii\db\Migration;

class m160217_114853_create_web_news extends Migration
{
    public function up()
    {
        $this->createTable('web_news', [
            'id' => $this->primaryKey(),
            'base_id' => $this->integer()->notNull(),
            'language_id' => $this->integer()->notNull(),
            'body' => $this->text()->notNull(),
            'title' => $this->string(128)->notNull(),
            'description' => $this->string(255),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey('fk-web_news-base_news', 'web_news', 'base_id', 'base_news', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk-web_news-languages', 'web_news', 'language_id', 'languages', 'id', 'restrict', 'cascade');
    }

    public function down()
    {
        $this->dropTable('web_news');
    }
}

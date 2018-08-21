<?php

use yii\db\Migration;

class m160217_115712_create_base_news_base_tag extends Migration
{
    public function up()
    {
        $this->createTable('base_news_base_tag', [
            'base_news_id' => $this->integer()->notNull(),
            'base_tag_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);

        $this->addPrimaryKey('pk-base_news_base_tag', 'base_news_base_tag', ['base_news_id', 'base_tag_id']);
        $this->addForeignKey('fk-base_news_base_tag-base_news', 'base_news_base_tag', 'base_news_id', 'base_news', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk-base_news_base_tag-base_tags', 'base_news_base_tag', 'base_tag_id', 'base_tags', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropTable('base_news_base_tag');
    }
}

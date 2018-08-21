<?php

use yii\db\Migration;

class m160303_154626_update_web_news_add_fulltextindex extends Migration
{
    public function up()
    {
        $this->execute('CREATE FULLTEXT INDEX fts_web_news_title ON web_news(title)');
        $this->execute('CREATE FULLTEXT INDEX fts_web_news_body ON web_news(body)');

    }

    public function down()
    {
        $this->dropIndex('fts_web_news_title', 'web_news');
        $this->dropIndex('fts_web_news_body', 'web_news');
    }

}

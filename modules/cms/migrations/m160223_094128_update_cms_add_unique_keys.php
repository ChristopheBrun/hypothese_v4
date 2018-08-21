<?php

use yii\db\Migration;

class m160223_094128_update_cms_add_unique_keys extends Migration
{
    public function up()
    {
        $this->createIndex('un-web_news-base_id-language_id', 'web_news', ['base_id', 'language_id'], true);
        $this->createIndex('un-web_pages-base_id-language_id', 'web_pages', ['base_id', 'language_id'], true);
        $this->createIndex('un-web_tags-base_id-language_id', 'web_tags', ['base_id', 'language_id'], true);
        $this->createIndex('un-web_texts-base_id-language_id', 'web_texts', ['base_id', 'language_id'], true);

    }

    public function down()
    {
        $this->dropIndex('un-web_news-base_id-language_id', 'web_news');
        $this->dropIndex('un-web_pages-base_id-language_id', 'web_pages');
        $this->dropIndex('un-web_tags-base_id-language_id', 'web_tags');
        $this->dropIndex('un-web_texts-base_id-language_id', 'web_texts');
    }

}

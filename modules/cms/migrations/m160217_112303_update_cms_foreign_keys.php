<?php

use yii\db\Migration;

/**
 * Class m160217_112303_update_cms_foreign_keys
 * On passe les colonnes id de int(10) unsigned not null auto_increment au format par défaut : int(11) not nullauto_increment
 * On met les clés étrangères à jour
 */
class m160217_112303_update_cms_foreign_keys extends Migration
{
    public function up()
    {
        $this->dropForeignKey('wpwtp_base_text_fk', 'base_page_base_text');
        $this->dropForeignKey('wpwtp_web_page_fk', 'base_page_base_text');
        $this->dropForeignKey('fk-web_pages-base_pages', 'web_pages');
        $this->dropForeignKey('fk-web_pages-languages', 'web_pages');
        $this->dropForeignKey('fk_base_text', 'web_texts');
        $this->dropForeignKey('fk_web_text_language', 'web_texts');
        $this->dropForeignKey('fk_parent_page_id', 'base_pages');

        $this->alterColumn('languages', 'id', 'INTEGER(11) NOT NULL AUTO_INCREMENT');
        $this->alterColumn('base_pages', 'id', 'INTEGER(11) NOT NULL AUTO_INCREMENT');
        $this->alterColumn('base_texts', 'id', 'INTEGER(11) NOT NULL AUTO_INCREMENT');

        $this->alterColumn('web_pages', 'language_id', 'INTEGER(11) NOT NULL');
        $this->alterColumn('web_texts', 'language_id', 'INTEGER(11) NOT NULL');
        $this->alterColumn('web_pages', 'base_id', 'INTEGER(11) NOT NULL');
        $this->alterColumn('web_texts', 'base_id', 'INTEGER(11) NOT NULL');
        $this->alterColumn('base_page_base_text', 'base_page_id', 'INTEGER(11) NOT NULL');
        $this->alterColumn('base_page_base_text', 'base_text_id', 'INTEGER(11) NOT NULL');
        $this->alterColumn('base_pages', 'parent_page_id', 'INTEGER(11)');

        $this->addForeignKey('fk-web_pages-languages', 'web_pages', 'language_id', 'languages', 'id', 'restrict', 'cascade');
        $this->addForeignKey('fk-web_texts-languages', 'web_texts', 'language_id', 'languages', 'id', 'restrict', 'cascade');
        $this->addForeignKey('fk-web_pages-base_pages', 'web_pages', 'base_id', 'base_pages', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk-web_texts-base_texts', 'web_texts', 'base_id', 'base_texts', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk-base_page_base_text-base_pages', 'base_page_base_text', 'base_page_id', 'base_pages', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk-base_page_base_text-base_texts', 'base_page_base_text', 'base_text_id', 'base_texts', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk-base_pages-base_pages', 'base_pages', 'parent_page_id', 'base_pages', 'id', 'set null', 'cascade');
    }

    public function down()
    {
        echo "m160217_112303_update_cms_foreign_keys cannot be reverted.\n";

        return false;
    }

}

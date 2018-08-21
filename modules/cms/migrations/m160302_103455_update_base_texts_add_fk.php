<?php

use yii\db\Migration;

/**
 * Class m160302_103455_update_base_texts_add_fk
 */
class m160302_103455_update_base_texts_add_fk extends Migration
{
    public function up()
    {
        $this->addForeignKey('fk-base_texts-base_pages', 'base_texts', 'base_page_id', 'base_pages', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropForeignKey('fk-base_texts-base_page', 'base_texts');
    }
}

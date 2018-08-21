<?php

use yii\db\Migration;

/**
 * Class m160302_104157_update_base_texts_indexes
 *
 * On supprime l'index unique sur base_texts.code
 * On le remplace par un index normal
 */
class m160302_104157_update_base_texts_indexes extends Migration
{
    public function up()
    {
        $this->dropIndex('base_text_idx_code', 'base_texts');
        $this->createIndex('idx-base_texts-code', 'base_texts', 'code');
    }

    public function down()
    {
        $this->dropIndex('idx-base_texts-code', 'base_texts');
        $this->createIndex('base_text_idx_code', 'base_texts', 'code', true);
    }

}

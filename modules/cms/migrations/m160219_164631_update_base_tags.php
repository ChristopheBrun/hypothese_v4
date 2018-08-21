<?php

use yii\db\Migration;

class m160219_164631_update_base_tags extends Migration
{
    public function up()
    {
        $this->addColumn('base_tags', 'code', $this->string(50)->notNull());
    }

    public function down()
    {
        $this->dropColumn('base_tags', 'code');
    }
}

<?php

use yii\db\Migration;

class m160217_115213_create_base_tags extends Migration
{
    public function up()
    {
        $this->createTable('base_tags', [
            'id' => $this->primaryKey(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('base_tags');
    }
}

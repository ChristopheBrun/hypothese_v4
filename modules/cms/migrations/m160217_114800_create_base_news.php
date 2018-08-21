<?php

use yii\db\Migration;

class m160217_114800_create_base_news extends Migration
{
    public function up()
    {
        $this->createTable('base_news', [
            'id' => $this->primaryKey(),
            'event_date' => $this->date(),
            'enabled' => $this->boolean()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('base_news');
    }
}

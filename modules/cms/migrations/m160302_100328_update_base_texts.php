<?php

use yii\db\Migration;

/**
 * Class m160302_100328_update_base_texts
 *
 * Ajout d'une colonne base_page_id pour faire un lien page -> textes de cardinalité 1-n au lieu du n-n actuel
 */
class m160302_100328_update_base_texts extends Migration
{
    public function up()
    {
        $this->addColumn('base_texts', 'base_page_id', $this->integer()->notNull() . ' AFTER id');
        // la clé étrangère devra être ajoutée après que les données auront été mises à jour
    }

    public function down()
    {
        $this->dropColumn('base_texts', 'base_page_id');
    }
}

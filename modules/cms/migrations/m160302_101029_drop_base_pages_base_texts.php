<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m160302_101029_drop_base_pages_base_texts
 *
 * Suppression de la table d'association base_page_base_text.
 * Les liens enregistrés dans cette table dont d'abord reportés dans base_texts.base_page_id
 */
class m160302_101029_drop_base_pages_base_texts extends Migration
{
    public function up()
    {
        $this->migrateData();
        $this->dropTable('base_page_base_text');
    }

    public function down()
    {
        // pas de retour en arrière autorisé ici tant que l'inverse de migrateData() n'est pas codé
        echo "m160302_101029_drop_base_pages_base_texts cannot be reverted.\n";
        return false;
    }

    /**
     * Transfert des liens de la table base_page_base_text vers la table base_texts
     */
    private function migrateData()
    {
        $rows = (new Query())
            ->select('base_page_id, base_text_id')
            ->from('base_page_base_text')
            ->all();

        /** @noinspection SqlResolve */
        $command = Yii::$app->db->createCommand("UPDATE base_texts SET base_page_id = :pageId WHERE id = :textId");
        foreach($rows as $row) {
            $command
                ->bindParam(':pageId', $row['base_page_id'])
                ->bindParam(':textId', $row['base_text_id'])
                ->execute();
        }
    }
}


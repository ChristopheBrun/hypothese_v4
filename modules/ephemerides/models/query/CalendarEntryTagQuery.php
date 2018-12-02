<?php

namespace app\modules\ephemerides\models\query;

use app\modules\ephemerides\models\CalendarEntryTag;

use Yii;
use yii\db\ActiveQuery;


/**
 * Class CalendarEntryTagQuery
 * @package app\modules\ephemerides\query
 */
class CalendarEntryTagQuery extends ActiveQuery
{

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct(CalendarEntryTag::class, $config);
    }

    /**
     * @inheritdoc
     * @returnRole[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @returnRole|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Mise à jour des associations entre l'éphéméride $entryId et ses tags.
     *
     * @param array $oldTagsIds
     * @param array $updatedTagsIds
     * @param       $entryId
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function updateTagsForCalendarEntry(array $oldTagsIds, array $updatedTagsIds, $entryId)
    {
        $out = true;
        $deletedTagsIds = array_diff($oldTagsIds, $updatedTagsIds);
        if ($deletedTagsIds) {
            $deleted = CalendarEntryTag::deleteAll(['calendar_entry_id' => $entryId, 'tag_id' => $deletedTagsIds]);
            $out &= ($deleted > 0);
        }

        $insertedTagsIds = array_diff($updatedTagsIds, $oldTagsIds);
        if ($insertedTagsIds) {
            $batch = [];
            $date = date('Y-m-d H:i:s');
            foreach ($insertedTagsIds as $id) {
                $batch[] = [$id, $entryId, $date, $date];
            }
            $inserted = Yii::$app->db->createCommand()->batchInsert('calendar_entry_tag',
                ['tag_id', 'calendar_entry_id', 'created_at', 'updated_at'],
                $batch)->execute();
            $out &= ($inserted > 0);
        }

        return $out;
    }

}
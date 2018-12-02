<?php

namespace app\modules\ephemerides\models;

use app\modules\ephemerides\models\query\CalendarEntryTagQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "calendar_entry_tag".
 *
 * @property string        $calendar_entry_id
 * @property string        $tag_id
 * @property string        $created_at
 * @property string        $updated_at
 *
 * @property CalendarEntry $calendarEntry
 * @property Tag           $tag
 */
class CalendarEntryTag extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calendar_entry_tag';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => function () {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendarEntry()
    {
        return $this->hasOne(CalendarEntry::class, ['id' => 'calendar_entry_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tag::class, ['id' => 'tag_id']);
    }

    /**
     * @inheritdoc
     * @return CalendarEntryTagQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CalendarEntryTagQuery();
    }

}

<?php

namespace app\modules\ephemerides\models\query;

use app\modules\ephemerides\models\CalendarEntry;
use Carbon\Carbon;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;


/**
 * Class CalendarEntryQuery
 * @package app\modules\ephemerides\query
 */
class CalendarEntryQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct(CalendarEntry::class, $config);
    }

    /**
     * Renvoie les noms des images classés par ordre alphabétique et indexés par l'identifiant de l'éphéméride
     *
     * @return array
     * @throws Exception
     */
    public static function getImages()
    {
        $sql = 'SELECT id, image FROM calendar_entry WHERE image IS NOT NULL AND image != \'\' ORDER BY image';
        return Yii::$app->db->createCommand($sql, ['active' => true])->queryAll();
    }

    /**
     * @inheritdoc
     * @return CalendarEntry[] | array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CalendarEntry | array | null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return static
     */
    public function enabled()
    {
        return $this->andWhere('enabled = 1');
    }

    /**
     * Renvoie la dernière éphémérides mise à jour
     *
     * @return CalendarEntry|array|null
     */
    public function lastUpdated()
    {
        return $this->enabled()->addOrderBy('updated_at DESC')->limit(1)->one();
    }

    /**
     * Renvoie le nombre de jours calendaires pour lesquels existe au moins une éphéméride active
     *
     * @return int
     * @throws Exception
     */
    public static function countDaysWithEntries()
    {
        $sql = 'SELECT COUNT(DISTINCT DAY(event_date), MONTH(event_date)) AS count FROM calendar_entry WHERE enabled = :active';
        return (int)Yii::$app->db->createCommand($sql, ['active' => true])->queryScalar();
    }

    /**
     * Sélectionne les éphémérides du jour calendaire (mois/jour) correspondant à $date
     *
     * @param string $date
     * @param string $format
     * @return static
     */
    public function byDay($date, $format = 'Y-m-d')
    {
        /** @var Carbon $date */
        $date = Carbon::createFromFormat($format, $date);
        return $this
            ->andWhere('DAY(event_date) = :day', ['day' => $date->day])
            ->andWhere('MONTH(event_date) = :month', ['month' => $date->month]);
    }

    /**
     * @param string $order
     * @return static
     */
    public function orderByDate($order = 'ASC')
    {
        return $this->addOrderBy('event_date ' . $order);
    }

    /**
     * Sélectionne les entrées précédant $entry en les classant par ordre chronologique décroissant
     *
     * @param CalendarEntry $entry
     * @return $this
     */
    public function previousInChronology(CalendarEntry $entry)
    {
        // select * from calendar_entries
        // where event_date = $entry->event_date and id < $entry->id OR event_date < $entry->event_date
        // order by event_date desc, id desc
        return $this->andWhere(['or', ['and', 'event_date = :date', 'id < :id'], 'event_date < :date'])
            ->addOrderBy('event_date DESC, id DESC')
            ->addParams(['date' => $entry->event_date, 'id' => $entry->id]);
    }

    /**
     * Sélectionne les entrées suivant $entry en les classant par ordre chronologique croissant
     *
     * @param CalendarEntry $entry
     * @return $this
     */
    public function nextInChronology(CalendarEntry $entry)
    {
        // select * from calendar_entries
        // where event_date = $entry->event_date and id > $entry->id OR event_date > $entry->event_date
        // order by event_date, id
        return $this
            ->andWhere(['or', ['and', 'event_date = :date', 'id > :id'], 'event_date > :date'])
            ->addOrderBy('event_date, id')
            ->addParams(['date' => $entry->event_date, 'id' => $entry->id]);
    }

    /**
     * Renvoie la première éphéméride trouvée avant $date.
     *
     * @param mixed $date La date de référence. Elle doit être soit un timestamp, soit une date formatée
     * @param null $format Si la date est formatée, le format est passé en argument pour renseigner Carbon
     * @param string $dayCompOperator
     * @return CalendarEntry|null
     * @throws Exception
     */
    public static function lastEntryBeforeCalendarDate($date, $format = null, $dayCompOperator = '<')
    {
        $date = is_integer($date) ? Carbon::createFromTimestamp($date) : Carbon::createFromFormat($format, $date);

        $dayComp = "DAY(event_date) $dayCompOperator :day";
        $sql = "SELECT *, 100 * MONTH(event_date) + DAY(event_date) AS day_rank
            FROM calendar_entry
            WHERE (MONTH(event_date) < :month
            OR (MONTH(event_date) = :month AND $dayComp))
            AND (enabled = :active)
            ORDER BY day_rank DESC, event_date DESC, id DESC
            LIMIT 1";

        $reader = Yii::$app->db
            ->createCommand($sql, ['active' => true, 'day' => $date->day, 'month' => $date->month])
            ->query();

        $row = $reader->read();
        if ($row === false) {
            if ($date->day != 31 && $date->month != 12) {
                // Si aucune éphéméride disponible entre la date du jour et le 1er janvier, on recommence la recherche à partir du 31/12
                return self::lastEntryBeforeCalendarDate('12-31', 'm-d', '<=');
            } else {
                return null;
            }
        } else {
            unset($row['day_rank']);
            return new CalendarEntry($row);
        }
    }

    /**
     * Renvoie la première éphéméride trouvée après $date.
     *
     * @param mixed $date La date de référence. Elle doit être soit un timestamp, soit une date formatée
     * @param string|null $format Si la date est formatée, le format est passé en argument pour renseigner Carbon
     * @param string $dayCompOperator
     * @return CalendarEntry|null
     * @throws Exception
     */
    public static function nextEntryAfterCalendarDate($date, $format = null, $dayCompOperator = '>')
    {
        $date = is_integer($date) ? Carbon::createFromTimestamp($date) : Carbon::createFromFormat($format, $date);

        $dayComp = "DAY(event_date) $dayCompOperator :day";
        $sql = "SELECT *, 100 * MONTH(event_date) + DAY(event_date) AS day_rank
        FROM calendar_entry
        WHERE (MONTH(event_date) > :month
        OR (MONTH(event_date) = :month AND $dayComp))
        AND (enabled = :active)
        ORDER BY day_rank, event_date, id
        LIMIT 1";

        $reader = Yii::$app->db
            ->createCommand($sql, ['active' => true, 'day' => $date->day, 'month' => $date->month])
            ->query();

        $row = $reader->read();
        if ($row === false) {
            if ($date->day != '01' && $date->month != '01') {
                // Si aucune éphéméride disponible entre la date du jour et le 31 décembre, on recommence la recherche à partir du 01/01
                return static::nextEntryAfterCalendarDate('01-01', 'm-d', ">=");
            } else {
                return null;
            }
        } else {
            unset($row['day_rank']);
            return new CalendarEntry($row);
        }
    }

    /**
     * @param int $id
     * @return $this
     */
    public function byTagId($id)
    {
        return $this
            ->innerJoin('calendar_entry_tag cet', 'cet.calendar_entry_id = calendar_entries.id AND cet.tag_id = :tagId')
            ->addParams(['tagId' => $id]);
    }

    /**
     * Met à NULL le champ 'image' de l'éphéméride $entryId
     *
     * @param $entryId
     * @return int
     * @throws Exception
     */
    public static function setImageAsNull($entryId)
    {
        $sql = 'UPDATE calendar_entry SET image = NULL WHERE id = :id';
        return Yii::$app->db->createCommand($sql, ['id' => $entryId])->execute();
    }

}
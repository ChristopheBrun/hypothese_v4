<?php

namespace app\modules\ephemerides\models\query;

use app\modules\ephemerides\models\CalendarEntry;
use DateTimeImmutable;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;


/**
 * Class CalendarEntryQuery
 * @package app\modules\ephemerides\query
 */
class CalendarEntryQuery extends ActiveQuery
{
    public function __construct($config = [])
    {
        parent::__construct(CalendarEntry::class, $config);
    }

    /**
     * Renvoie les noms des images classés par ordre alphabétique et indexés par l'identifiant de l'éphéméride
     * @return array
     * @throws Exception
     */
    public static function getImages(): array
    {
        $sql = 'SELECT id, image FROM calendar_entry WHERE image IS NOT NULL AND image != \'\' ORDER BY image';
        return Yii::$app->db->createCommand($sql, ['active' => true])->queryAll();
    }

    /**
     * @return CalendarEntry[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    public function one($db = null): array|CalendarEntry|null
    {
        return parent::one($db);
    }

    public function enabled(): CalendarEntryQuery
    {
        return $this->andWhere('enabled = 1');
    }

    /**
     * Renvoie la dernière éphémérides mise à jour
     * @return CalendarEntry|array|null
     */
    public function lastUpdated(): array|CalendarEntry|null
    {
        return $this->enabled()->addOrderBy('updated_at DESC')->limit(1)->one();
    }

    /**
     * Renvoie le nombre de jours calendaires pour lesquels existe au moins une éphéméride active
     * @return int
     * @throws Exception
     */
    public static function countDaysWithEntries(): int
    {
        $sql = 'SELECT COUNT(DISTINCT DAY(event_date), MONTH(event_date)) AS count 
            FROM calendar_entry 
            WHERE enabled = :active';
        return (int)Yii::$app->db->createCommand($sql, ['active' => true])->queryScalar();
    }

    /**
     * Sélectionne les éphémérides du jour calendaire (mois/jour) correspondant à $date
     * @param string $date
     * @param string $format
     * @return static
     */
    public function byDay(string $date, string $format = 'Y-m-d'): CalendarEntryQuery
    {
        $date = DateTimeImmutable::createFromFormat($format, $date);
        return $this
            ->andWhere('DAY(event_date) = :day', ['day' => $date->format('d')])
            ->andWhere('MONTH(event_date) = :month', ['month' => $date->format('m')]);
    }

    /**
     * Sélectionne les éphémérides du jour calendaire (mois/jour) correspondant à $date
     * @param int $month
     * @param int $day
     * @return static
     */
    public function byMonthAndDay(int $month, int $day): CalendarEntryQuery
    {
        return $this
            ->andWhere('DAY(event_date) = :day', ['day' => $day])
            ->andWhere('MONTH(event_date) = :month', ['month' => $month]);
    }

    public function orderByDate(string $order = 'ASC'): CalendarEntryQuery
    {
        return $this->addOrderBy('event_date ' . $order);
    }

    /**
     * Sélectionne les entrées précédant $entry en les classant par ordre chronologique décroissant
     * @param CalendarEntry $entry
     * @return $this
     */
    public function previousInChronology(CalendarEntry $entry): CalendarEntryQuery
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
     * @param CalendarEntry $entry
     * @return $this
     */
    public function nextInChronology(CalendarEntry $entry): CalendarEntryQuery
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
     * @param mixed $date La date de référence. Elle doit être soit un timestamp, soit une date formatée
     * @param ?string $format Si la date est formatée, le format est passé en argument
     * @param string $dayCompOperator
     * @return CalendarEntry|null
     * @throws Exception
     */
    public static function lastEntryBeforeCalendarDate(
        mixed   $date,
        ?string $format = null,
        string  $dayCompOperator = '<'): ?CalendarEntry
    {
        $date = is_integer($date) ? (new DateTimeImmutable())->setTimestamp($date) : DateTimeImmutable::createFromFormat($format, $date);

        $dayComp = "DAY(event_date) $dayCompOperator :day";
        $sql = "SELECT *, 100 * MONTH(event_date) + DAY(event_date) AS day_rank
            FROM calendar_entry
            WHERE (MONTH(event_date) < :month
            OR (MONTH(event_date) = :month AND $dayComp))
            AND (enabled = :active)
            ORDER BY day_rank DESC, event_date DESC, id DESC
            LIMIT 1";

        $reader = Yii::$app->db
            ->createCommand($sql, ['active' => true, 'day' => $date->format('d'), 'month' => $date->format('m')])
            ->query();

        $row = $reader->read();
        if (!$row) {
            if ($date->format('d') != 31 && $date->format('m') != 12) {
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
     * @param mixed $date La date de référence. Elle doit être soit un timestamp, soit une date formatée
     * @param ?string $format Si la date est formatée, le format est passé en argument
     * @param string $dayCompOperator
     * @return ?CalendarEntry
     * @throws Exception
     */
    public static function nextEntryAfterCalendarDate(
        mixed   $date,
        ?string $format = null,
        string  $dayCompOperator = '>'): ?CalendarEntry
    {
        $date = is_integer($date) ? (new DateTimeImmutable())->setTimestamp($date) : DateTimeImmutable::createFromFormat($format, $date);

        $dayComp = "DAY(event_date) $dayCompOperator :day";
        $sql = "SELECT *, 100 * MONTH(event_date) + DAY(event_date) AS day_rank
            FROM calendar_entry
            WHERE (MONTH(event_date) > :month
            OR (MONTH(event_date) = :month AND $dayComp))
            AND (enabled = :active)
            ORDER BY day_rank, event_date, id
            LIMIT 1
        ";

        $reader = Yii::$app->db
            ->createCommand($sql, ['active' => true, 'day' => $date->format('d'), 'month' => $date->format('m')])
            ->query();

        $row = $reader->read();
        if (!$row) {
            if ($date->format('d') !== '01' && $date->format('m') !== '01') {
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

    public function byTagId(int $id): CalendarEntryQuery
    {
        return $this
            ->innerJoin('calendar_entry_tag cet', 'cet.calendar_entry_id = calendar_entries.id AND cet.tag_id = :tagId')
            ->addParams(['tagId' => $id]);
    }

    /**
     * Met à NULL le champ 'image' de l'éphéméride $entryId
     * @param int $entryId
     * @return int
     * @throws Exception
     */
    public static function setImageAsNull(int $entryId): int
    {
        $sql = 'UPDATE calendar_entry SET image = NULL WHERE id = :id';
        return Yii::$app->db->createCommand($sql, ['id' => $entryId])->execute();
    }

    /**
     * Renvoie la liste des jours calendaires ayant au moins une éphéméride active.
     * Les jours calendaires renvoyés sont au format 'mm-dd'.
     * @param string $sort
     * @return array
     * @throws Exception
     */
    public static function monthAndDaysWithEnabledEntries(string $sort = 'ASC'): array
    {
        $sql = "SELECT MONTH(event_date) AS month, DAY(event_date) AS day
            FROM calendar_entry
            WHERE enabled = 1
            GROUP BY month, day
            ORDER BY month, day $sort;
        ";

        $reader = Yii::$app->db->createCommand($sql)->query();
        $out = [];
        while ($row = $reader->read()) {
            $out[] = sprintf('%02d-%02d', $row['month'], $row['day']);
        }

        return $out;
    }

}
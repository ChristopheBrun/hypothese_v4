<?php

namespace app\modules\cms\queries;

use app\modules\cms\models\BaseNews;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\cms\models\BaseNews]].
 *
 * @see \app\modules\cms\models\BaseNews
 */
class BaseNewsQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct(BaseNews::class, $config);
    }


    /**
     * @inheritdoc
     * @return \app\modules\cms\models\BaseNews[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\cms\models\BaseNews|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Sélectionne les BaseNews qui ne sont associées à aucune WebNews
     *
     * @return $this
     */
    public function withoutWebNews()
    {
        return $this
            ->leftJoin('web_news w', 'w.base_id = base_news.id')
            ->where('w.id IS NULL');
    }

    /**
     * Renvoie la BaseNews immédiatement antérieure à celle d'identifiant $id & $eventDate
     *
     * @param int     $id
     * @param string  $eventDate
     * @param boolean $onlyActive true => seules les objets actifs sont renvoyés
     * @return $this
     */
    public function getPrevious($id, $eventDate, $onlyActive = true)
    {
        $this->andWhere('event_date < :eventDate OR (event_date = :eventDate AND id < :id)');

        if ($onlyActive) {
            $this->andWhere('enabled = 1');
        }

        return $this
            ->addOrderBy('event_date DESC, id DESC')
            ->limit(1)
            ->addParams(['eventDate' => $eventDate, 'id' => $id]);
    }

    /**
     * Renvoie la BaseNews immédiatement postérieure à celle d'identifiant $id & $eventDate
     *
     * @param int     $id
     * @param string  $eventDate
     * @param boolean $onlyActive true => seules les objets actifs sont renvoyés
     * @return $this
     */
    public function getNext($id, $eventDate, $onlyActive = true)
    {
        $this->andWhere('event_date > :eventDate OR (event_date = :eventDate AND id > :id)');

        if ($onlyActive) {
            $this->andWhere('enabled = 1');
        }

        return $this
            ->addOrderBy('event_date DESC, id DESC')
            ->limit(1)
            ->addParams(['eventDate' => $eventDate, 'id' => $id]);
    }

}
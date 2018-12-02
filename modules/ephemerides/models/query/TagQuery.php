<?php

namespace app\modules\ephemerides\models\query;

use app\modules\ephemerides\models\Tag;
use yii\db\ActiveQuery;


/**
 * Class TagQuery
 * @package app\modules\ephemerides\query
 */
class TagQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct(Tag::class, $config);
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
     * @return $this
     */
    public function orderByLabel()
    {
        return $this->addOrderBy('label');
    }

}
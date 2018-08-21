<?php

namespace app\modules\cms\queries;
use app\modules\cms\models\BaseTag;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\cms\models\BaseTag]].
 *
 * @see \app\modules\cms\models\BaseTag
 */
class BaseTagsQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct(BaseTag::class, $config);
    }


    /**
     * @inheritdoc
     * @return \app\modules\cms\models\BaseTag[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\cms\models\BaseTag|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Sélectionne les BaseTags qui ne sont associés à aucun WebTag
     *
     * @return $this
     */
    public function withoutWebTags()
    {
        return $this
            ->leftJoin('web_tags w', 'w.base_id = base_tags.id')
            ->where('w.id IS NULL');
    }

    /**
     * @param string $order
     * @return static
     */
    public function orderByCode($order = 'ASC')
    {
        return $this->addOrderBy('code ' . $order);
    }

}
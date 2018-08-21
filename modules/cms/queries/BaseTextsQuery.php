<?php

namespace app\modules\cms\queries;
use app\modules\cms\models\BaseText;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\cms\models\BaseText]].
 *
 * @see \app\modules\cms\models\BaseText
 */
class BaseTextsQuery extends ActiveQuery
{

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct(BaseText::class, $config);
    }

    /**
     * @inheritdoc
     * @return \app\modules\cms\models\BaseText[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\cms\models\BaseText|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param string $order
     * @return static
     */
    public function orderByCode($order = 'ASC')
    {
        return $this->addOrderBy('code ' . $order);
    }

    /**
     * Sélectionne les BaseTexts qui ne sont associés à aucun WebText
     *
     * @return $this
     */
    public function withoutWebTexts()
    {
        return $this
            ->leftJoin('web_texts w', 'w.base_id = base_texts.id')
            ->where('w.id IS NULL');
    }
}
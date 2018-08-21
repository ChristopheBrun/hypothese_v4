<?php

namespace app\modules\cms\queries;

use app\modules\cms\models\Language;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Language]].
 *
 * @see Language
 */
class LanguagesQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return Language[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Language|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Sélectionne les langages utilisés par les pages associées à la page/racine $basePageId
     *
     * @param $basePageId
     * @return $this
     */
    public function byBasePageId($basePageId)
    {
        return $this
            ->innerJoin('web_pages wp', 'wp.language_id = languages.id AND wp.base_id = :baseId')
            ->addParams(['baseId' => $basePageId]);
    }

    /**
     * Sélectionne les langages utilisés par les tertes associés au texte/racine $baseTextId
     *
     * @param $baseTextId
     * @return $this
     */
    public function byBaseTextId($baseTextId)
    {
        return $this
            ->innerJoin('web_texts wt', 'wt.language_id = languages.id AND wt.base_id = :baseId')
            ->addParams(['baseId' => $baseTextId]);
    }

    /**
     * Ajoute une clause de tri sur la colonne [[iso_639_code]]
     *
     * @param string $orderClause
     * @return $this
     */
    public function orderByCode($orderClause = 'ASC')
    {
        return $this
            ->addOrderBy('iso_639_code ' . $orderClause);
    }
}
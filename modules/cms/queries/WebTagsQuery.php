<?php

namespace app\modules\cms\queries;

use app\modules\cms\models\WebTag;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\cms\models\WebTag]].
 *
 * @see \app\modules\cms\models\WebTag
 */
class WebTagsQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct(WebTag::class, $config);
    }


    /**
     * @inheritdoc
     * @return \app\modules\cms\models\WebTag[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\cms\models\WebTag|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Filtre les tags en fonction du langage utilisé
     *
     * @param string $iso639code
     * @return $this
     */
    public function byLanguageCode($iso639code)
    {
        return $this
            ->innerJoin('languages', 'languages.id = language_id AND languages.iso_639_code = :languageCode')
            ->addParams(['languageCode' => $iso639code]);
    }

    /**
     * Filtre les tags en fonction du langage utilisé
     *
     * @param int $languageId
     * @return $this
     */
    public function byLanguageId($languageId)
    {
        return $this
            ->andWhere('language_id = :langId')
            ->addParams(['langId' => $languageId]);
    }

    /**
     * Renvoie la liste des tags associés à la WebNews #$webNewsId.
     * NB : seuls les tags traduits dans la langue de la WebNews sont sélectionnés
     *
     * @param $webNewsId
     * @return $this
     */
    public function forWebNews($webNewsId)
    {
        return $this
            ->innerJoin('base_tags', 'base_tags.id = web_tags.base_id')
            ->innerJoin('base_news_base_tag', 'base_news_base_tag.base_tag_id = base_tags.id')
            ->innerJoin('base_news', 'base_news.id = base_news_base_tag.base_news_id')
            ->innerJoin('web_news', 'web_news.base_id = base_news.id')
            ->where('web_news.id = :id AND web_tags.language_id = web_news.language_id')
            ->addParams(['id' => $webNewsId]);
    }

    /**
     * @return $this
     */
    public function orderByLabel()
    {
        return $this->addOrderBy('label');
    }
}
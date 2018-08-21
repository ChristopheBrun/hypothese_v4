<?php

namespace app\modules\cms\queries;

use app\modules\cms\models\WebNews;
use app\modules\hlib\helpers\h;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\cms\models\WebNews]].
 *
 * @see \app\modules\cms\models\WebNews
 */
class WebNewsQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct(WebNews::class, $config);
    }

    /**
     * @inheritdoc
     * @return \app\modules\cms\models\WebNews[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\cms\models\WebNews|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $iso639Code
     * @return $this
     */
    public function byLanguageCode($iso639Code)
    {
        return $this
            ->innerJoin('languages', 'languages.id = web_news.language_id')
            ->andWhere('languages.iso_639_code = :code')
            ->addParams([':code' => $iso639Code]);
    }

    /**
     * Renvoie les $nbNews dernières actualités. Par défaut, on sélectionne la langue de l'application
     *
     * @param string  $languageCode
     * @param int     $nbNews
     * @param boolean $getOnlyActiveNews
     * @return $this
     */
    public function lastNews($nbNews, $getOnlyActiveNews = true, $languageCode = null)
    {
        if (is_null($languageCode)) {
            $languageCode = h::getIso639Code(Yii::$app->language);
        }

        $this->joinWith('base');
        if ($getOnlyActiveNews) {
            $this->andWhere('base_news.enabled = 1');
        }

        return $this
            ->byLanguageCode($languageCode)
            ->addOrderBy('base_news.event_date DESC')
            ->limit($nbNews);
    }

    /**
     * Sélectionne les WebNews associées au WebTag d'identifiant $tagId
     *
     * @param      $tagId
     * @param bool $joinWithBase Par défaut, on fait la jointure avec la table base_news. S'il arrive que cette jointure
     * soit déjà faite dans les traitements, il faut éviter le doublon et mettre ce paramètre à false
     * @return $this
     */
    public function byTagId($tagId, $joinWithBase = true)
    {
        if ($joinWithBase) {
            $this->innerJoin('base_news', 'base_news.id = base_id');
        }

        return $this
            ->innerJoin('base_news_base_tag', 'base_news_base_tag.base_news_id = base_news.id')
            ->innerJoin('base_tags', 'base_tags.id = base_news_base_tag.base_tag_id')
            ->innerJoin('web_tags', 'web_tags.base_id = base_tags.id')
            ->andWhere('web_tags.id = :tagId')
            ->addParams(['tagId' => $tagId]);
    }

    /**
     * Sélectionne les webNews dont le texte contient la chaîne $substring
     *
     * @param $substring
     * @return $this
     */
    public function bodyContains($substring)
    {
        return $this
            ->andWhere('MATCH (body) AGAINST (:body)', ['body' => $substring]);
    }

    /**
     * Sélectionne les webNews dont le titre contient la chaîne $substring
     *
     * @param $substring
     * @return $this
     */
    public function titleContains($substring)
    {
        return $this
            ->andWhere('MATCH (title) AGAINST (:title)', ['title' => $substring]);
    }

    /**
     * @param $id
     * @return $this
     */
    public function byBaseNewsId($id)
    {
        return $this
            ->andWhere('base_id = :baseNewsId')
            ->addParams(['baseNewsId' => $id]);
    }

    /**
     * @param $id
     * @return $this
     */
    public function byLanguageId($id)
    {
        return $this
            ->andWhere('language_id = :languageId')
            ->addParams(['languageId' => $id]);
    }

}
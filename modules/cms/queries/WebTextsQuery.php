<?php

namespace app\modules\cms\queries;

use app\modules\cms\models\WebPage;
use app\modules\cms\models\WebText;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\cms\models\WebText]].
 *
 * @see \app\modules\cms\models\WebText
 */
class WebTextsQuery extends ActiveQuery
{

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct(WebText::class, $config);
    }

    /**
     * @inheritdoc
     * @return \app\modules\cms\models\WebText[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\cms\models\WebText|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Cherche un texte associé au texte/racine de code $baseTextCode
     *
     * @param string $code
     * @return $this
     * @deprecated
     */
    public function byBaseTextCode($code)
    {
        return $this
            ->innerJoin('base_texts', 'base_texts.id = base_id AND base_texts.code = :code')
            ->addParams(['code' => $code]);
    }

    /**
     * Cherche tous les textes associés à la page/racine $basePageId.
     * Les résultats sont classés sur la colonne [[base_texts.code]]
     *
     * @param int $basePageId
     * @return $this
     */
    public function byBasePageId($basePageId)
    {
        return $this
            ->innerJoin('base_texts', 'base_texts.id = web_texts.base_id')
            ->andWhere('base_texts.base_page_id = :basePageId')
            ->orderBy('base_texts.code')
            ->addParams(['basePageId' => $basePageId]);
    }

    /**
     * Cherche tous les textes associés à la page d'identifiant $pageId, c'est à dire liés à la même page/racine et
     * partageant la même langue.
     * Les résultats sont classés sur la colonne [[base_texts.code]]
     *
     * @param WebPage $page
     * @return $this
     */
    public function byWebPage(WebPage $page)
    {
        return $this
            ->byBasePageId($page->base_id)
            ->byLanguageId($page->language_id);
    }

    /**
     * Cherche tous les textes associés à la page d'identifiant $pageId, c'est à dire liés à la même page/racine et
     * partageant la même langue.
     * Les résultats sont classés sur la colonne [[base_texts.code]]
     *
     * @param WebPage $page
     * @param string  $code
     * @return $this
     */
    public function byWebPageAndCode(WebPage $page, $code)
    {
        return $this
            ->byWebPage($page)
            ->andWhere('base_texts.code = :code')
            ->addParams(['code' => $code]);
    }

    /**
     * Filtre les textes en fonction du langage utilisé
     *
     * @param string $iso639code
     * @return $this
     */
    public function byLanguageCode($iso639code)
    {
        return $this
            ->innerJoin('languages l', 'l.id = web_texts.language_id AND l.iso_639_code = :languageCode')
            ->addParams(['languageCode' => $iso639code]);
    }

    /**
     * Filtre les textes en fonction du langage utilisé
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

}
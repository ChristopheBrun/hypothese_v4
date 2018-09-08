<?php

namespace app\modules\cms\queries;

use app\modules\cms\models\WebPage;
use app\modules\hlib\helpers\h;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\cms\models\WebPage]].
 *
 * @see \app\modules\cms\models\WebPage
 */
class WebPagesQuery extends ActiveQuery
{

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct(WebPage::class, $config);
    }

    /**
     * @inheritdoc
     * @return \app\modules\cms\models\WebPage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\cms\models\WebPage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Cherche une page associée à la page/racine de code $basePageCode
     *
     * @param string      $basePageCode
     * @param bool        $enabledOnly
     * @param string|null $languageCode
     * @return $this
     */
    public function byCode($basePageCode, $enabledOnly = true, $languageCode = null)
    {
        if (is_null($languageCode)) {
            $languageCode = h::getIso639Code(Yii::$app->language);
        }

        $this->innerJoin('base_pages b', 'b.id = base_id AND b.code = :code');
        if ($enabledOnly) {
            $this->andWhere('b.enabled = 1');
        }

        return $this
            ->innerJoin('languages l', 'l.id = language_id AND l.iso_639_code = :langCode')
            ->addParams(['langCode' => $languageCode, 'code' => $basePageCode]);
    }

    /**
     * Filtre les textes en fonction du langage utilisé
     *
     * @param string $iso639code
     * @return $this
     */
    public function language($iso639code)
    {
        return $this
            ->innerJoin('languages l', 'l.id = language_id AND l.iso_639_code = :code')
            ->addParams(['code' => $iso639code]);
    }

    /**
     * Cherche toutes les pages associées au texte/racine $baseTextId.
     * Les résultats sont classés sur la colonne [[base_page.code]]
     *
     * @param int $baseTextId
     * @return $this
     */
    public function byBaseTextId($baseTextId)
    {
        return $this
            ->innerJoin('base_pages', 'base_pages.id = web_pages.base_id')
            ->innerJoin('base_texts', 'base_texts.base_page_id = base_pages.id')
            ->andWhere('base_texts.id = :baseTextId')
            ->orderBy('base_pages.code')
            ->addParams(['baseTextId' => $baseTextId]);
    }

    /**
     * Sélectionne les pages publiées dans la langue $languageCode et susceptibles de servir d'éléments de menu pour le menu principal du site
     *
     * @param      $languageCode
     * @param bool $onlyWithRoutes
     * @return $this
     */
    public function asMenuItems($languageCode, $onlyWithRoutes = true)
    {
        $this
            ->language($languageCode)
            ->innerJoin('base_pages b', 'b.id = base_id')
            ->andWhere('b.menu_index IS NOT NULL AND b.enabled = 1')
            ->addOrderBy('b.menu_index, b.code');

        if ($onlyWithRoutes) {
            $this->andWhere('b.route IS NOT NULL AND b.route != \'\'');
        }

        return $this;
    }

}
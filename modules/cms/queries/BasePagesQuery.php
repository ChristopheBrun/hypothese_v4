<?php

namespace app\modules\cms\queries;

use app\modules\cms\models\BasePage;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * This is the ActiveQuery class for [[\app\modules\cms\models\BasePage]].
 *
 * @see \app\modules\cms\models\BasePage
 */
class BasePagesQuery extends ActiveQuery
{

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct(BasePage::class, $config);
    }

    /**
     * @inheritdoc
     * @return \app\modules\cms\models\BasePage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\cms\models\BasePage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Sélectionne les pages de code $value
     *
     * @param $value
     * @return $this
     */
    public function code($value)
    {
        return $this
            ->andWhere('code = :code')
            ->addParams(['code' => $value]);
    }

    /**
     * Sélectionne les pages actives
     *
     * @return $this
     */
    public function enabled()
    {
        return $this
            ->andWhere('enabled = 1');
    }

    /**
     * Sélectionne les pages ayant une route associée (donc une url)
     *
     * @return $this
     */
    public function withRoute()
    {
        return $this
            ->andWhere('route IS NOT NULL AND route != \'\'');
    }

    /**
     * Sélectionne les BasePages qui ne sont associées à aucune WebPage
     *
     * @return $this
     */
    public function withoutWebPages()
    {
        return $this
            ->leftJoin('web_pages', 'web_pages.base_id = base_pages.id')
            ->where('web_pages.id IS NULL');
    }

    /**
     * Sélectionne les BasePages à qui on a affecté une position dans le menu du site
     *
     * @param bool $orderedByMenuIndex
     * @return $this
     */
    public function withMenuIndex($orderedByMenuIndex = true)
    {
        $this->andWhere('menu_index IS NOT NULL');

        if ($orderedByMenuIndex) {
            $this->addOrderBy('menu_index ASC');
        }

        return $this;
    }

    /**
     * Renvoie la liste des identifiants pages avec leur index de menu, classées par index croissant
     * Les pages sans index de menu ne sont pas remontées par cette requête.
     *
     * @return array [col_name => col_value]
     */
    public static function getMenuIndexesMap()
    {
        return (new Query())
            ->select('id, menu_index')
            ->from('base_pages')
            ->where('menu_index IS NOT NULL')
            ->orderBy('menu_index')
            ->all();
    }

    /**
     * @param int $id
     * @param     $menuIndex
     * @return int
     * @throws \yii\db\Exception
     */
    public static function updateMenuIndexes($id, $menuIndex)
    {
        $sql = /** @lang text */
            "UPDATE {{%base_pages}} SET [[menu_index]] = :idx WHERE id = :id";
        return Yii::$app->db->createCommand($sql, ['idx' => $menuIndex, 'id' => $id])->execute();
    }

    /**
     * Sélectionne les BasePages ayant le menu_idx $idx
     *
     * @param int $idx
     * @return $this
     */
    public function byMenuIndex($idx)
    {
        return $this
            ->andWhere('menu_index = :idx')
            ->addParams(['idx' => $idx]);
    }

    /**
     * @return $this
     */
    public function orderByCode()
    {
        return $this->addOrderBy('code');
    }
}
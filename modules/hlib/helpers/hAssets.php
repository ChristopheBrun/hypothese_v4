<?php

namespace app\modules\hlib\helpers;

use app\modules\hlib\HLib;
use Yii;
use yii\helpers\Html;


/**
 * Class hAssets
 * @package app\modules\hlib\helpers
 */
class hAssets
{
    /**
     * Renvoie une balise 'bootstrap' adaptée à la clause de tri.
     * On fait la différence entre un tri ascendant ($clause = 'asc'), descendant ($clause = 'desc') ou pas de tri ($clause = '')
     * Les icônes varient selon le type de données à trier : tri alphabétique ($type = 'alphabet'), numérique ($type = 'order'), autre
     *
     * @param string $clause Indique si le tri est fait par ordre croissant ou décroissant.
     * Valeurs attendues : 'asc' ou desc'
     * @param string $type Type de l'icone demandée : tri alphabétique, tri selon un ordre numérique, tri selon des attributs.
     * Valeurs attendues : 'alphabet', 'order', 'attributes'
     * @return string
     */
    public static function bootstrapSortGraphicTag($clause, $type = 'alphabet')
    {
        // Si on ne demande aucun tri, inutile d'afficher quoi que ce soit
        if ($clause != 'asc' && $clause != 'desc') {
            return '';
        }

        // L'icône renvoyée dépend du type de tri
        switch ($type) {
            case 'alphabet' :
                $out = $clause == 'asc' ?
                    '<span class="glyphicon glyphicon-sort-by-alphabet" aria-hidden="true"></span>' :
                    '<span class="glyphicon glyphicon-sort-by-alphabet-alt" aria-hidden="true"></span>';
                break;
            case 'order' :
                $out = $clause == 'asc' ?
                    '<span class="glyphicon glyphicon-sort-by-order" aria-hidden="true"></span>' :
                    '<span class="glyphicon glyphicon-sort-by-order-alt" aria-hidden="true"></span>';
                break;
            default :
                $out = $clause == 'asc' ?
                    '<span class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>' :
                    '<span class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>';
                break;
        }

        return $out;
    }

    /**
     * @param bool $bool
     * @param bool $showFalse
     * @return string
     */
    public static function getImageTagForBoolean($bool, $showFalse = true)
    {
        $out = "";
        if ($bool) {
            $out = Html::img(Yii::$app->homeUrl . 'images/ok-16.png', ['alt' => HLib::t('labels', 'yes')]);
        }
        else {
            if ($showFalse) {
                $out = Html::img(Yii::$app->homeUrl . 'images/ko-16.png', ['alt' => HLib::t('labels', 'no')]);
            }
        }

        return $out;
    }

}
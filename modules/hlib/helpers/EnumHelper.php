<?php

namespace app\modules\hlib\helpers;

use yii\helpers\ArrayHelper;


/**
 * Class EnumHelper
 * @package ia\helpers
 *
 * Classes de qualifiants : servent d'énumérations pour des listes de valeurs possibles, regroupées par domaine ou objet métier
 * Ces classes proposent :
 *      - une liste de constantes à exploiter dans le code et dans la base de données
 *      - une fonction qui renvoie la liste des constantes et le libellé associé
 *      - une fonction permettant de récupérer facilement un libellé en fonction de la constante
 */
abstract class EnumHelper
{
    /**
     * Renvoie un tableau associatif au format : constante de l'énum => libellé
     *
     * @return array
     */
    public static function getList()
    {
        return [];
    }

    /**
     * Renvoie la liste des clés (numériques en général) de l'enum
     *
     * @return array
     */
    public static function values()
    {
        return array_keys(static::getList());
    }

    /**
     * Renvoie true si la valeur $valeur fait partie de l'enum
     *
     * @param mixed $value
     * @param bool $strict
     * @return array
     */
    public static function containsValue($value, $strict = false)
    {
        return in_array($value, static::values(), $strict);
    }

    /**
     * Renvoie le libellé associé à la valeur $value
     *
     * @param mixed $value
     * @param string $default Valeur renvoyée par défaut si $value ne fait pas partie de l'enum
     * @return mixed
     */
    public static function getLabel($value, $default = '')
    {
        return ArrayHelper::getValue(static::getList(), $value, $default);
    }

    /**
     * Renvoie la liste des identifiants de l'enum
     *
     * @return array
     */
    public static function getKeys()
    {
        return array_keys(static::getList());
    }
}
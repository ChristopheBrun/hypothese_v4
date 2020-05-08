<?php

namespace app\modules\hlib\helpers;

use Stringy\Stringy;
use yii\helpers\StringHelper;


/**
 * Class hString
 * @package app\modules\hlib\helpers
 */
class hString extends StringHelper
{
    /**
     * Transliterate a UTF-8 value to ASCII.
     *
     * @param  string $value
     * @return string
     */
    public static function ascii($value)
    {
        return (new Stringy($value))->toAscii();
    }

    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  string $title
     * @param  string $separator
     * @return string
     * @deprecated utiliser plutôt yii\helpers\Inflector::slug()
     */
    public static function slugify($title, $separator = '-')
    {
        // NB : on force le transtypage en (string) ici car il ne marche pas toujours tout seul.
        // ex : dans Url::to(), liste des arguments de l'url, Stringy n'est pas transtypé...
        return '' . (new Stringy($title))->slugify($separator);
    }

    /**
     * Force l'encodage d'une chaine en UTF-8
     *
     * @param $string
     * @return string
     */
    public static function forceUTF8($string)
    {
        if (!mb_check_encoding($string, 'UTF-8') ||
            $string !== mb_convert_encoding(mb_convert_encoding($string, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32')
        ) {
            $string = mb_convert_encoding($string, 'UTF-8');
        }

        return $string;
    }

}
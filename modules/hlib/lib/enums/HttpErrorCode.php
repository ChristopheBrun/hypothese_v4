<?php

namespace app\modules\hlib\lib\enums;

use app\modules\hlib\helpers\EnumHelper;
use app\modules\hlib\HLib;

/**
 * Class HttpErrorCode
 * @todo_cbn Liste à compléter au fur et à mesure des besoins
 */
class HttpErrorCode extends EnumHelper
{
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const PAYMENT_REQUIRED = 402;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;

    /**
     * NB : pour pouvoir exploiter la méthode getShortLabel(), les messages associés aux différents codes d'erreur doivent respecter
     * le format suivant :
     *      libellé court : description plus précise du code d'erreur
     *
     * @return array
     */
    public static function getList()
    {
        return [
            // codes 400
            static::BAD_REQUEST => HLib::t('http', 'Request error : your request may have syntax errors'),
            static::UNAUTHORIZED => HLib::t('http', 'Authentication required : this is not a public resource'),
            static::PAYMENT_REQUIRED => HLib::t('http', 'Payment required : this is not a free resource'),
            static::FORBIDDEN => HLib::t('http', 'Access forbidden : this resource is hidden or you do not own the required privileges'),
            static::NOT_FOUND => HLib::t('http', 'Page not found : the URL is invalid or deprecated'),
            static::METHOD_NOT_ALLOWED => HLib::t('http', 'Method not allowed : this resource requires another access method'),
        ];
    }

    /**
     * Renvoie le libellé court associé à la valeur $value.
     * On appelle libelé court la partie de trouvant avant le ':'
     *
     * @param mixed  $value
     * @param string $default Valeur renvoyée par défaut si $value ne fait pas partie de l'enum
     * @return mixed
     */
    public static function getShortLabel($value, $default = '')
    {
        $label = static::getLabel($value, $default);
        $pos = mb_strpos($label, ':');
        return $pos !== false ? trim(mb_substr($label, 0, $pos + 1)) : $label;
    }

}
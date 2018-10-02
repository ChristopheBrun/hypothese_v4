<?php

namespace app\modules\hlib\lib;

use yii\base\Exception;

/**
 * Class DisplayableException
 * @package app\modules\ia\lib
 *
 * Exception générique à utiliser quand le message qui lui est associé est affichable sur le navigateur du client
 * Ex : exception lancée pendant une action avec un message générique tel que "Erreur de validation"
 * Ne pas utiliser cette classe si le message contient des informations techniques de nature à troubler l'utilisateur
 */
class DisplayableException extends Exception
{

}
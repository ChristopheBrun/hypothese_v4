<?php


namespace app\modules\hlib\widgets;


use app\modules\hlib\HLib;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class hWidget
 * @package app\modules\hlib\widgets
 */
class hWidget extends Widget
{
    /**
     * Vérifie que les attributs obligatoires déclarés dans $requiredAttrs sont bien renseignés
     *
     * @param string[] $requiredAttrs
     * @throws InvalidConfigException
     */
    protected function checkRequiredAttributes(array $requiredAttrs)
    {
        $missingAttrs = [];
        foreach ($requiredAttrs as $attr) {
            // isset() renvoie false si l'attribut vaut NULL, il faut donc doubler le test pour accepter les attributs NULL
            // et ne refuser que ceux qui n'ont pas du tout été initialisés
            if (!isset($this->$attr) && !is_null($this->$attr)) {
                $missingAttrs[] = $attr;
            }
        }

        if ($missingAttrs) {
            $str = implode(', ', $missingAttrs);
            throw new InvalidConfigException(HLib::t('messages', "Missing parameters : {0}", [$str]));
        }
    }
}
<?php

namespace app\modules\hlib\lib;

use yii\base\Component;
use yii\db\ActiveRecord;


/**
 * Class ActiveFieldAttributeOptions
 * @package app\modules\hlib\lib
 *
 * Classe permettant de désactiver un contrôle de formulaire si l'attribut du modèle associé n'est pas un attribut actif
 */

// Exemples
//
// 1 - désactive la liste de boutons radio "Sexe" si l'attribut Patient::$sex est inactif dans le scénario en cours :
// $patientField = new AttributeOptions(['model' => $patient]);
// $form->field($patient, 'sex')->radioList(Sex::getList(), ['itemOptions' => $patientField->disable('sex')])

class ActiveFieldAttributeOptions extends Component
{
    /** @var  ActiveRecord */
    public $model;

    /**
     * Renvoie true si le champ $attributeName fait partie des attributs actifs du modèle
     *
     * @param string $attributeName
     * @return bool
     */
    public function isActive($attributeName)
    {
        return in_array($attributeName, $this->model->activeAttributes());
    }

    /**
     * Ajoute une ligne 'disabled' dans le tableau 'options' utilisé par l'ActiveField qui appelle cette méthode
     *
     * @param string $attributeName
     * @param array  $options
     * @param bool   $value
     * @return array
     */
    public function disable($attributeName, $options = [], $value = true)
    {
        if (!$this->isActive($attributeName)) {
            $options['disabled'] = $value;
        }

        return $options;
    }

    /**
     * Ajoute une ligne 'readonly' dans le tableau 'options' utilisé par l'ActiveField qui appelle cette méthode
     *
     * @param string $attributeName
     * @param array  $options
     * @param bool   $value
     * @return array
     */
    public function readonly($attributeName, $options = [], $value = true)
    {
        if (!$this->isActive($attributeName)) {
            $options['readonly'] = $value;
        }

        return $options;
    }
}
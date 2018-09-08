<?php

namespace app\modules\hlib\widgets;

use app\modules\hlib\HLib;
use Closure;
use Yii;
use yii\bootstrap\Widget;
use yii\db\ActiveRecord;


/**
 * Class DisplayModels
 * @package app\modules\hlib\widgets
 *
 * Ce widget permet d'afficher une liste de liens vers les vues (backend) des modèles passés en argument.
 */
class DisplayModels extends Widget
{
    /** @var array|ActiveRecord $models Instance Tableau des modèles à afficher dans la liste */
    public $models;

    /** @var string $labelField Nom de la propriété à lire sur le modèle pour afficher un libellé */
    public $labelField;

    /** @var string $labelMethod Nom de la méthode à appeler sur le modèle pour afficher un libellé */
    public $labelMethod = '';

    /**
     * @var Closure $labelCallback Fonction anonyme à appeler pour afficher un libellé
     * Signature attendue : function(ActiveRecord $model) : return string
     */
    public $labelCallback;

    /** @var boolean $checkEnabled */
    public $checkEnabled = false;

    /** @var string $listType Suffixe du template choisi pour le rendu de la liste : ['ul', 'ol', 'div'] */
    public $listType = 'div';

    /** @var string Préfixe du template à utiliser pour le rendu de la liste */
    public $templateName = 'displayModels';

    /**
     * @return string
     */
    public function run()
    {
        if (!$this->models) {
            return '';
        }

        if (!is_array($this->models)) {
            $this->models = [$this->models];
        }

        if (is_callable($this->labelMethod)) {
            $this->labelCallback = $this->labelMethod;
            $this->labelMethod = '';
        }

        $template = $this->templateName . '_' . $this->listType;
        return $this->render($template);
    }

    /**
     * @param ActiveRecord $model
     * @return string
     */
    public function retrieveLabel(ActiveRecord $model)
    {
        if (isset($this->labelCallback)) {
            $lambda = $this->labelCallback;
            return $lambda($model);
        }

        if (isset($this->labelMethod) && $model->hasMethod($this->labelMethod)) {
            return call_user_func([$model, $this->labelMethod]);
        }

        if (isset($this->labelField) && array_key_exists($this->labelField, $model->fields())) {
            return $model->{$this->labelField};
        }

        Yii::error(HLib::t('messages', "Unable to retrieve a label for these models"));
        return '???';
    }
}
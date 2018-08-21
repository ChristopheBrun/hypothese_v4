<?php

namespace app\modules\hlib\widgets;

use Closure;
use yii\base\InvalidConfigException;
use yii\bootstrap\Widget;
use yii\db\ActiveRecord;


/**
 * Class ViewModelsList
 * @package app\modules\hlib\widgets
 *
 * Ce widget permet d'afficher une liste de liens vers les vues (backend) des modèles passés en argument.
 */
class ViewModelsList extends Widget
{

    /** @var array|ActiveRecord $models Instance Tableau des modèles à afficher dans la liste */
    public $models;

    /** @var string $labelField Nom de la propriété à lire sur le modèle pour afficher un libellé */
    public $labelField;

    /** @var string $labelMethod Nom de la méthode à appeler sur le modèle pour afficher un libellé */
    public $labelMethod = '';

    /** @var Closure $labelCallback Nom de la méthode à appeler sur le modèle pour afficher un libellé */
    public $labelCallback;

    /** @var string $controllerRoute */
    public $controllerRoute = '';

    /** @var boolean $checkEnabled */
    public $checkEnabled = false;

    /** @var string $listType Nom du template choisi pour le rendu de la liste : ['ul', 'ol', 'div'] */
    public $listType = 'ol';

    /**
     * @return string
     */
    public function run()
    {
        if (!$this->models) {
            return '';
        }

        if(!is_array($this->models)) {
            $this->models = [$this->models];
        }

        if(is_callable($this->labelMethod)) {
            $this->labelCallback = $this->labelMethod;
            $this->labelMethod = '';
        }

        switch($this->listType) {
            case 'div' :
                $template = 'viewModelsList_div';
                break;
            case 'ul' :
                $template = 'viewModelsList_ul';
                break;
            default :
                $template = 'viewModelsList_ol';
                break;
        }

        return $this->render($template);
    }

    /**
     * @param ActiveRecord $model
     * @return string
     * @throws InvalidConfigException
     */
    public function retrieveLabel(ActiveRecord $model)
    {
        if (isset($this->labelCallback)) {
//            return $this->labelCallback->call($model); // à partir de PHP 7 uniquement...
            return call_user_func($this->labelCallback, $model);
        }

        if (isset($this->labelMethod) && $model->hasMethod($this->labelMethod)) {
            return call_user_func([$model, $this->labelMethod]);
        }

        if (isset($this->labelField) && array_key_exists($this->labelField, $model->fields())) {
            return $model->{$this->labelField};
        }

        throw new InvalidConfigException('Impossible de récupérer un libellé pour ces modèles');
    }
}
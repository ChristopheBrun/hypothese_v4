<?php

namespace app\modules\hlib\widgets;

use yii\bootstrap\Html;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * Class DisplayModelsWithLinks
 * @package app\modules\hlib\widgets
 *
 * Ce widget permet d'afficher une liste de liens vers les vues (backend) des modèles passés en argument.
 */
class DisplayModelsWithLinks extends DisplayModels
{
    /** @var string $controllerRoute */
    public $controllerRoute = '';

    /**
     * @var string Permet de fournir une autre route que 'view'
     */
    public $controllerView = 'view';

    /** @var string Préfixe du template à utiliser pour le rendu de la liste */
    public $templateName = 'displayModelsWithLinks';

    /** @var bool true => l'id du modèle et le nom du champ correspondant à la clé primaire sont ajoutés comme arguments de l'url */
    public $allowUrlQuery = true;

    /**
     * Renvoie l'url de la page de consultation du modèle
     *
     * @param ActiveRecord $model
     * @return string
     */
    public function getViewUrl(ActiveRecord $model)
    {
        $pkName = $model->primaryKey()[0];
        return $this->allowUrlQuery ?
            Html::a($this->retrieveLabel($model), Url::to([$this->controllerRoute . '/' . $this->controllerView, $pkName => $model->getPrimaryKey()]))
            : Html::a($this->retrieveLabel($model), Url::to([$this->controllerRoute . '/' . $this->controllerView]));
    }

}
<?php

namespace app\modules\hlib\widgets;
use yii\bootstrap\Widget;
use yii\helpers\Url;


/**
 * Class GridListActionButtons
 * @package app\modules\hlib\widgets
 */
class GridListActionButtons extends Widget
{

    /** @var string $controllerRoute Chemin d'accès au contrôleur (ex. : /calendar-entries, /cms/base-pages) */
    public $controllerRoute;

    /** @var int Identifiant du modèle à traiter */
    public $modelId;

    /** @var string Informations affichées en complément du message d'avertissement quand on clique sur le bouton 'supprimer' */
    public $deleteMessageData;

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('gridListActionButtons', [
                'updateUrl' => Url::to([$this->controllerRoute . '/update', 'id' => $this->modelId]),
                'viewUrl' => Url::to([$this->controllerRoute . '/view', 'id' => $this->modelId]),
                'deleteUrl' => Url::to([$this->controllerRoute . '/delete', 'id' => $this->modelId]),
                'deleteMessageData' => $this->deleteMessageData,
            ]
        );
    }

}
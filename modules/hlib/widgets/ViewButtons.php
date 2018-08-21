<?php

namespace app\modules\hlib\widgets;

use yii\bootstrap\Widget;
use yii\helpers\Url;


/**
 * Class ViewButtons
 * @package app\modules\hlib\widgets
 */
class ViewButtons extends Widget
{

    /** @var string $controllerPath Accès au contrôleur (ex. : /calendar-entries, /cms/base-pages) */
    public $controllerPath;

    /** @var int */
    public $modelId;

    /** @var int $indexPage Si cette propriété est renseignée, on l'utilise comme numéro de page pour l'action 'index' */
    public $indexPage = null;

    /** @var array $additionalButtons tableau de tableaux au format avec les clés : label, class, url */
    public $additionalButtons = null;

    /**
     * @return string
     */
    public function run()
    {
        if (!is_null($this->indexPage)) {
            $indexUrl = Url::to([$this->controllerPath . '/index', 'page' => $this->indexPage]);
        }
        else {
            $indexUrl = Url::to([$this->controllerPath . '/index']);
        }

        return $this->render('viewButtons', [
                'indexUrl' => $indexUrl,
                'updateUrl' => Url::to([$this->controllerPath . '/update', 'id' => $this->modelId]),
                'deleteUrl' => Url::to([$this->controllerPath . '/delete', 'id' => $this->modelId]),
                'additionalButtons' => $this->additionalButtons
            ]
        );
    }
}
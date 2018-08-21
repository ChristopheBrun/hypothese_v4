<?php

namespace app\modules\hlib\widgets;
use yii\bootstrap\Widget;


/**
 * Class SubmitButtons
 * @package app\modules\hlib\widgets
 */
class SubmitButtons extends Widget
{
    /** @var string $indexUrl URL de la page d'index pour la redirection 'retour liste' */
    public $indexUrl;

    /** @var string $deleteUrl URL facultative de l'action de delete. Si elle est renseignée, un bouton 'supprimer' sera affiché */
    public $deleteUrl;

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('submitButtons');
    }

}
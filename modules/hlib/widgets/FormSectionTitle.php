<?php

namespace app\modules\hlib\widgets;

use yii\bootstrap\Widget;

/**
 * Class FormSectionTitle
 * @package app\modules\hlib\widgets
 *
 * Affiche une barre de titre pour une section du formulaire
 * Elle comprend une icone (de la série font-awesome-icons par défaut) et un libellé
 */
class FormSectionTitle extends Widget
{
    //
    // Paramètres de configuration obligatoires
    //

    /** @var string */
    public $label;

    //
    // Paramètres de configuration facultatifs
    //

    /** @var string */
    public $templateName = 'formSectionTitle';

    /** @var string */
    public $iconClass = 's';

    /** @var string */
    public $moreHtml = 0;

    /**
     * @return string
     */
    public function run()
    {
        return $this->render($this->templateName, [
            'iconClass' => $this->iconClass,
            'label' => $this->label,
            'moreHtml' => $this->moreHtml,
        ]);
    }
}
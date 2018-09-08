<?php

namespace app\modules\hlib\widgets;

use yii\bootstrap\Widget;
use yii\helpers\Url;

/**
 * Class CookiesDirective
 * @package app\modules\hlib\widgets
 *
 * Affiche le message d'avertissement sur l'uitilisation de cookies.
 * L'url de la page affichant la poiltique de confidentialité est configurable
 * Basé sur le plugin jquery.cookiesdirective.js
 */
class CookiesDirective extends Widget
{
    /** @var string */
    public $privacyPolicyRoute = '/site/privacy';

    /**
     * @return string
     */
    public function run()
    {
        $url = Url::to($this->privacyPolicyRoute);
        return "$(document).ready(function () {
        $.cookiesDirective({
            privacyPolicyUri: '$url',
            message: \"Ce site utilise des cookies pour réaliser des statistiques de visites et d'utilisation des boutons sociaux.\",
            explicitConsent: false
        });
    });
";
    }
}
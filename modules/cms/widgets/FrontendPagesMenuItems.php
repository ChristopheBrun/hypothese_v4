<?php

namespace app\modules\cms\widgets;
use app\modules\cms\models\WebPage;
use Yii;
use yii\base\Widget;


/**
 * Class FrontendPagesMenuItems
 * @package app\modules\cms\widgets
 */
class FrontendPagesMenuItems extends Widget
{
    /** @var string Code ISO 639 du langage à utiliser. Par défaut, on prendra celui de l'application */
    public $languageCode;

    /**
     * Récupère la liste des pages qui doivent apparaître dans le menu principal en frontend
     * Construit le code HTML pour afficher ces éléments de menu
     */
    public function run()
    {
        if(!isset($this->languageCode)) {
            $this->languageCode = mb_substr(Yii::$app->language, 0, 2);
        }

        $pages = WebPage::find()->asMenuItems($this->languageCode)->all();
        if(count($pages)) {
            return $this->render('frontendPagesMenuItems', compact('pages'));
        }
        else {
            return '';
        }
    }

}
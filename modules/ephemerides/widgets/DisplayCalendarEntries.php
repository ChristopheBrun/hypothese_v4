<?php


namespace app\modules\ephemerides\widgets;

use app\modules\ephemerides\models\CalendarEntry;
use app\modules\hlib\widgets\hWidget;
use yii\base\InvalidConfigException;

/**
 * Class DisplayCalendarEntries
 * @package app\modules\ephemerides\widgets
 */
class DisplayCalendarEntries extends hWidget
{
    /**
     * @var CalendarEntry[]|CalendarEntry $models
     *      Liste des éphémérides à afficher (un bloc par éphéméride)
     */
    public $models;

    /**
     * @var string|array $tagsButtonsAltRoute
     *      null => les catégories sont affichées sous forme d'un bouton avec un lien vers les éphémérides de cette catégorie
     *      route renseignée => les catégories sous la forme d'un lien utilisant cette route
     */
    public $tagsButtonsAltRoute = null;

    /**
     * @var string $templateName
     *      Préfixe du template à utiliser pour le rendu de la liste
     */
    public string $templateName = 'displayCalendarEntries';

    /**
     * @var bool $showAdminButton
     *      true => on affiche un bouton avec un lien vers l'écran de vue backend pour l'éphéméride
     */
    public bool $showAdminButton = false;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->checkRequiredAttributes(['models']);
    }

    /**
     * @return string
     */
    public function run(): string
    {
        if (!is_array($this->models)) {
            $this->models = [$this->models];
        }

        return $this->render($this->templateName, [
            'models' => $this->models,
            'tagsButtonsAltRoute' => $this->tagsButtonsAltRoute,
            'showAdminButton' => $this->showAdminButton,
        ]);
    }

}
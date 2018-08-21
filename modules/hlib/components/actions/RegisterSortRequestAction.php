<?php

namespace app\modules\hlib\components\actions;

use app\modules\hlib\tools\ListSorter;
use Yii;
use yii\base\Action;
use yii\helpers\Url;

/**
 * Class CalendarEntriesIndexAction
 * @package app\components\actions
 */
class RegisterSortRequestAction extends Action
{
    /** @var string */
    public $sessionKeyForSortClause;

    /** @var string */
    public $sessionKeyForPageClause;

    /** @var string */
    public $redirectToUrl;

    /**
     * Traitement des demandes de tri / sur les colonnes : on enregistre en session le critère de tri demandé.
     *
     * @param string $orderBy Nom de la colonne sur laquelle on demande un tri. Celui-ci boucle sur 3 états : asc/desc/n-a
     */
    public function run($orderBy)
    {
        ListSorter::updateSession($orderBy, $this->sessionKeyForSortClause);
        $currentPage = Yii::$app->session->get($this->sessionKeyForPageClause, 1);
        $this->controller->redirect(Url::to([$this->redirectToUrl, 'page' => $currentPage]));
    }
}
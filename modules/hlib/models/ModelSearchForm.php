<?php

namespace app\modules\hlib\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * Class ModelSearchForm
 *
 * Classe de base pour gérer les formulaires de recherche
 *
 * @package app\modules\hlib\models
 */
abstract class ModelSearchForm extends Model
{
    const ERR_OK = 0;
    const ERR_KO = 1;

    const ST_ALL = '';

    const ST_ENABLED = 1;
    const ST_DISABLED = 0;

    const ST_WITH = 1;
    const ST_WITHOUT = 0;

    /** @var string $sessionKey Clé du cookie de session pour mémoriser les filtres */
    protected $sessionKey;

    public $error = self::ERR_OK;

    /**
     * @return string
     */
    public function formName()
    {
        return '';
    }

    /**
     * Enregistre les filtres en session
     *
     * @param array $filters
     */
    public function storeFiltersInSession(array $filters)
    {
        Yii::$app->session->set($this->sessionKey, $filters);
    }

    /**
     * Renvoie le tableau des filtres qui a été stocké en session
     *
     * @return array
     */
    public function retrieveFiltersFromSession()
    {
        return Yii::$app->session->get($this->sessionKey, []);
    }

    /**
     * Supprime les filtres stockés en session
     */
    public function deleteFiltersInSession()
    {
        Yii::$app->session->remove($this->sessionKey);
    }

    /**
     * Renvoie true si un filtre a été demandé
     * NB : cette méthode ne garantit pas la validité des filtres saisis. Elle indique simplement qu'il y en a un.
     *
     * @return bool
     */
    public abstract function hasActiveFilters();

    /**
     * Renvoie une chaine de caractères décrivant les filtres actifs
     *
     * @param string $sep
     * @return string
     */
    public abstract function displayActiveFilters($sep = ' - ');

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public abstract function search(array $params);
}
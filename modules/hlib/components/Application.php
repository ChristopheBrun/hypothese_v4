<?php

namespace app\modules\hlib\components;

use Yii;


/**
 * Class Application
 *
 * @package app\components
 */
abstract class Application extends \yii\web\Application
{
    /**
     * @inheritdoc
     */
    public function preInit(&$config)
    {
        parent::preInit($config);
        $this->checkSecuredUrls($config);
    }

    /**
     * Renvoie l'URL sécurisée pour l'application (https), à renseigner dans les classes dérivées
     *
     * @return string
     */
    protected abstract function getBaseSecuredUrl();

    /**
     * Renvoie l'URL non sécurisée pour l'application (http), à renseigner dans les classes dérivées
     *
     * @return string
     */
    protected abstract function getBaseUrl();

    /**
     * En production, on s'assure que les pages sensibles sont accédées en https
     * On cherche les urls à sécuriser dans config/web.php
     * Elle sont stockées dans 'securedUrls' au format /login, /forgot_password, etc...
     *
     * @param $config
     */
    protected function checkSecuredUrls(array $config)
    {
        if (YII_ENV_DEV || !array_key_exists('securedUrls', $config['params'])) {
            return;
        }

        if (in_array($_SERVER['REQUEST_URI'], $config['params']['securedUrls']) && strtolower($_SERVER['REQUEST_SCHEME']) != 'https') {
            // Une url sécurisée est redirigée sur https si le protocole est http
            header('Location: ' . $this->getBaseSecuredUrl() . $_SERVER['REQUEST_URI']);
        }
        elseif (!in_array($_SERVER['REQUEST_URI'], $config['params']['securedUrls']) && strtolower($_SERVER['REQUEST_SCHEME']) == 'https') {
            // Une url non sécurisée est redirigée vers http si le protocole est https
            header('Location: '. $this->getBaseUrl() .$_SERVER['REQUEST_URI']);
        }
    }
}
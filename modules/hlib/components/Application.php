<?php

namespace app\modules\hlib\components;

use yii\base\InvalidConfigException;

/**
 * Class Application
 * @package app\components
 */
class Application extends \yii\web\Application
{
    /** @var string[] $keywordsMetaTags */
    protected array $keywordsMetaTags = [];

    /**
     * Permet de s'assurer que le site utilise le bon encodage et la bonne localisation
     *
     * @param array $config
     * @throws InvalidConfigException
     * @noinspection PhpMissingParamTypeInspection
     */
    public function preInit(&$config)
    {
        parent::preInit($config);

        // Encodage et langue
        mb_internal_encoding('UTF-8');
        list($code, $lang) = $config['params']['locale'] ?? 'fr';
        setlocale(LC_TIME, $code, $lang);

        // Réglage de la timezone sinon date() renvoie l'heure UTC
        $timezone = $config['params']['timezone'] ?? 'Europe/Paris';
        date_default_timezone_set($timezone);
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function addKeywordsMetaTag(string $tag): Application
    {
        $this->keywordsMetaTags[] = $tag;
        return $this;
    }

    /**
     * @param array $tags
     * @return $this
     */
    public function addKeywordsMetaTags(array $tags): Application
    {
        $this->keywordsMetaTags = array_merge($this->keywordsMetaTags, $tags);
        return $this;
    }

    /**
     * @return string[]
     */
    public function getKeywordsMetaTags(): array
    {
        return array_unique($this->keywordsMetaTags);
    }

}
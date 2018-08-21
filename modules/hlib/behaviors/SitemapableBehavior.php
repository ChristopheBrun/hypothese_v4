<?php

namespace app\modules\hlib\behaviors;

use Closure;
use SimpleXMLElement;
use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;


/**
 * Class SitemapableBehavior
 *
 * @package app\modules\cms\helpers
 */
class SitemapableBehavior extends Behavior
{
    /**
     * @var string Squelette permettant de créer le SimpleXMLElement de départ
     */
    private $xmlContainer = "<?xml version='1.0' standalone='yes'?>
         <urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'></urlset>
    ";

    /**
     * @var Closure Cette callback doit renvoyer les noeuds XML du sitemap pour la classe concernée
     * Signature : SimpleXMLelement function (SimpleXMLElement $xml)
     */
    public $callback;

    /**
     * @var string Nom du fichier .xml à créer
     */
    public $filename;

    /**
     * @var string Nom du répertoire public de l'application
     */
    public $publicDirectory = 'public_html';

    /**
     *
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'onAfterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'onAfterSave',
            ActiveRecord::EVENT_AFTER_DELETE => 'onAfterSave',
        ];
    }

    /**
     * @param Event $event
     */
    public function onAfterSave(/** @noinspection PhpUnusedParameterInspection */
        Event $event)
    {
        $this->generateSitemap();
    }

    /**
     * Construit et sauvegarde le fichier .xml du sitemap de ce modèle
     *
     * @return bool
     */
    public function generateSitemap()
    {
        $xml = new SimpleXMLElement($this->xmlContainer);
        $xml = call_user_func($this->callback, $xml);
        $filepath = Yii::getAlias('@app') . '/' . $this->publicDirectory . '/' . $this->filename;
        return $xml->saveXML($filepath);
    }

}
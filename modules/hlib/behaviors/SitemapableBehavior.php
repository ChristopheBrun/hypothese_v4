<?php /** @noinspection PhpUnusedParameterInspection */

/** @noinspection PhpUnused */

namespace app\modules\hlib\behaviors;

use app\modules\hlib\HLib;
use Closure;
use SimpleXMLElement;
use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;


/**
 * Class SitemapableBehavior
 * @package app\modules\cms\helpers
 *
 * Permet d'alimenter automatiquement un fichier sitemap.xml à destination des moteurs de recherche
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
    public $publicDirectory;

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
     * @throws InvalidConfigException
     */
    public function onAfterSave(Event $event)
    {
        $this->generateSitemap();
    }

    /**
     * Construit et sauvegarde le fichier .xml du sitemap de ce modèle
     *
     * @return bool
     * @throws InvalidConfigException
     */
    public function generateSitemap()
    {
        $xml = new SimpleXMLElement($this->xmlContainer);
        $xml = call_user_func($this->callback, $xml);
        $filepath = sprintf('%s/%s/%s',
            Yii::getAlias('@app'), $this->retrievePublicDirectoryName(), $this->filename);
        return $xml->saveXML($filepath);
    }

    /**
     * Renvoie le nom du répertoire web del'application.
     * S'il est renseigné dans la configuration de $this, on prned ce qui a été paramétré.
     * Sinon, on prend le premier répertoire trouvé parmi public_html, www et web.
     * Si aucun répertoire n'est identifiable, on lance une exception
     *
     * @return string
     * @throws InvalidConfigException
     */
    private function retrievePublicDirectoryName()
    {
        if (isset($this->publicDirectory)) {
            return $this->publicDirectory;
        }

        $dirnames = ['public_html', 'www', 'web'];
        $appDir = Yii::getAlias('@app');
        foreach ($dirnames as $dirname) {
            if (is_dir("$appDir/$dirname")) {
                return $dirname;
            }
        }

        throw new InvalidConfigException(HLib::t('messages', "No valid public directory for " . __CLASS__));
    }

}
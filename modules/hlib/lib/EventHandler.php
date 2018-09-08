<?php

namespace app\modules\hlib\lib;

use yii\base\Component;
use yii\base\NotSupportedException;
use app\modules\hlib\HLib;


/**
 * Class EventHandler
 * @package app\modules\hlib\lib
 *
 * Classe de base pour les gestionnaires d'événements.
 * Elle fournit le singleton qui permet l'abonnement du gestionnaire aux événements qui l'intéressent.
 * Pour utiliser un gestionnaire de cette famille, il faut en créer une instance temporaire au lancement de l'application ou du module
 * afin d'initialiser le singleton et d'abonner le gestionnaire aux événements qu'il doit suivre par défaut (@see subscribeEvents())
 */
abstract class EventHandler extends Component
{
    /** @var  EventHandler */
    protected static $singleton;

    /**
     * Singleton à appeler au lancement de l'application ou du module pour qu'on puisse s'abonner aux événements qui nous intéressent
     * @see \app\components\Application::init()
     *
     * @return EventHandler
     * @throws NotSupportedException
     */
    public static function singleton()
    {
        if (!static::$singleton) {
            static::$singleton = new static();
        }

        return static::$singleton;
    }

    /**
     * @param array $config
     * @throws NotSupportedException
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        if (!static::$singleton) {
            static::$singleton = $this;
            $this->subscribeEvents();
        }
        else {
            throw new NotSupportedException(HLib::t('messages', "Only one instance allowed"));
        }
    }

    /**
     * @return mixed
     */
    protected abstract function subscribeEvents();

}
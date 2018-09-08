<?php

namespace app\components;

use app\modules\hlib\HLib;
use app\modules\user\UserModule;

// L'autoloader n'est pas encore chargé, il faut faire le require explicitement
require_once(__DIR__ . '/../modules/hlib/components/Application.php');

/**
 * Class Application
 * @package app\components
 */
class Application extends \app\modules\hlib\components\Application
{
    /**
     *
     */
    public function init()
    {
        parent::init();

        // On initialise les singletons pour qu'ils s'abonnent aux événements qu'ils doivent suivre
//        MailerEventHandler::singleton();
//        DefaultEventHandler::singleton();

        // On enregistre les ressources pour les chaines de caractères internationalisées
        // @todo voir s'il ne faut pas forcer le chergement du module avec load()
        // @todo voir pourquoi les modules déclarés dans 'bootstrap' n'ont pas l'air d'être automatiquement chargés...
        HLib::registerTranslations();
        UserModule::registerTranslations();
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
//        $behaviors['blockedUsers'] = [
//            'class' => \app\behaviors\BlockedUserFilter::className(),
//        ];
        return $behaviors;
    }

}
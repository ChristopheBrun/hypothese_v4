<?php

namespace app\components;

// L'autoloader n'est pas encore chargé, il faut faire le require explicitement
require_once(__DIR__ . '/../modules/hlib/components/Application.php');

/**
 * Class Application
 * @package app\components
 */
class Application extends \app\modules\hlib\components\Application
{
//    /**
//     *
//     */
//    public function init()
//    {
//        parent::init();
//
//        // On initialise les singletons pour qu'ils s'abonnent aux événements qu'ils doivent suivre
////        MailerEventHandler::singleton();
////        DefaultEventHandler::singleton();
//
//        // On enregistre les ressources pour les chaines de caractères internationalisées
//        // @todo_cbn voir s'il ne faut pas forcer le chergement du module avec load()
//        // @todo_cbn voir pourquoi les modules déclarés dans 'bootstrap' n'ont pas l'air d'être automatiquement chargés
//        HLib::registerTranslations();
//        UserModule::registerTranslations();
//    }

}
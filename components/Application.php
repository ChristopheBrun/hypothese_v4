<?php

namespace app\components;

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
        setlocale(LC_ALL, 'fr_FR');
//
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
    }

}
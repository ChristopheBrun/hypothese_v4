<?php

namespace app\controllers;

use app\components\APIResult;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;


/**
 * Class ApiController
 * @package app\controllers
 */
class ApiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'testBasicAuth' => ['get'],
                ],
            ],
//            'access' => [
//                'class' => AccessControl::class,
//                'rules' => [
//                    [
//                        'actions' => ['test-basic-auth'],
//                        'allow' => true,
//                    ],
//                ],
//            ],
            'authentication' => [
                'class' => HttpBasicAuth::class,
                'only' => ['test-basic-auth'],
            ],
            'contentNegociation' => [
                'class' => ContentNegotiator::class,
                'only' => ['test-basic-auth'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ]
        ];
    }

    /**
     * Permet de vérifier que l'authentification BasicAuth passe bien
     * Principaux points de blocage possibles :
     *      - mauvaise clé d'API
     *      - Apache ne transmet pas PHP_AUTH_USER
     *      - le composant User n'identifie pas correctement le toke  (type ou valeur à vérifier)
     *
     * @return Response|string
     */
    public function actionTestBasicAuth()
    {
        $out = new APIResult();
        $out->data['date'] = date('Y-m-d H:i:s');
        $out->data['infos'] = "Authentification réussie";
        return $out;
    }


    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////


}

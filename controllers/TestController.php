<?php

namespace app\controllers;

use yii\web\Controller;


/**
 * Class TestController
 * @package app\controllers
 */
class TestController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
//            'verbs' => [
//                'class' => VerbFilter::class,
//                'actions' => [
//                    'testBasicAuth' => ['get'],
//                ],
//            ],
//            'access' => [
//                'class' => AccessControl::class,
//                'rules' => [
//                    [
//                        'actions' => ['index', 'contact', 'error', 'captcha'],
//                        'allow' => true,
//                    ],
//                ],
//            ],
//            'authentication' => [
//                'class' => HttpBasicAuth::class,
//                'only' => ['test-basic-auth'],
//            ],
//            'contentNegociation' => [
//                'class' => ContentNegotiator::class,
//                'only' => ['test-basic-auth'],
//                'formats' => [
//                    'application/json' => Response::FORMAT_JSON,
//                ],
//            ]
        ];
    }

//    /**
//     * Permet de vérifier que l'authentification BasicAuth passe bien
//     * Points de blocage possibles : mauvaisé clé d'API, Apache ne transmet pas PHP_AUTH_USER
//     *
//     * @return Response|string
//     */
//    public function actionTestBasicAuth()
//    {
//        $out = new APIResult();
//        $out->data['date'] = date('Y-m-d H:i:s');
//        $out->data['infos'] = "Authentification réussie";
//        return $out;
//    }


    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////


}

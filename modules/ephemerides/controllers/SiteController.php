<?php

namespace app\modules\ephemerides\controllers;

use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\form\CalendarEntrySearchForm;
use app\modules\ephemerides\models\Tag;
use app\modules\hlib\behaviors\GoogleAnalyticsBehavior;
use app\modules\hlib\components\actions\ErrorAction;
use app\modules\hlib\helpers\h;
use Yii;
use yii\captcha\CaptchaAction;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\ContactForm;
use yii\web\Response;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    public bool $enableGoogleAnalytics = false;

    public function behaviors(): array
    {
        return [
            // En frontend, seule l'action de test est réservée à l'admin, les autres sont accessibles à tout le monde
            'access-admin' => [
                'class' => AccessControl::class,
                'only' => ['test'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['test'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'google-analytics' => [
                'class' => GoogleAnalyticsBehavior::class,
                'except' => ['test', 'offline'],
            ]
        ];
    }

    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex(): string
    {
        // Récupération d'une liste éventuellement filtrée selon les critères du moteur de recherche
        $searchModel = new CalendarEntrySearchForm();

        // les éphémérides du jour (avec les tables associées pour limiter le nombre de requêtes)
        $dailyEntries = CalendarEntry::find()->enabled()->byDay(date('Y-m-d'))->orderByDate()->all();

        $tags = ArrayHelper::map(Tag::find()->orderByLabel()->all(), 'id', 'label');
        return $this->render('index', compact('dailyEntries', 'searchModel', 'tags'));
    }

    public function actionContact(): Response|string
    {
        $model = new ContactForm();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->sendMail()) {
            // Formulaire soumis sans erreur
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }

        // Affichage initial ou ré-affichage après erreur de validation
        return $this->render('contact', compact('model'));
    }

    public function actionAbout(): string
    {
        return $this->render('about');
    }

    public function actionOffline(): string
    {
        return $this->renderPartial('offline');
    }

    public function actionTest(): string
    {
        return $this->render('test');
    }

}

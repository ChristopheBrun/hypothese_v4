<?php

namespace app\modules\ephemerides\controllers;

use app\modules\cms\models\WebPage;
use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\form\CalendarEntrySearchForm;
use app\modules\ephemerides\models\Tag;
use app\modules\hlib\behaviors\GoogleAnalyticsBehavior;
use app\modules\hlib\components\actions\ErrorAction;
use app\modules\hlib\helpers\h;
use Yii;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\ContactForm;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    public $enableGoogleAnalytics = false;

    /**
     * @return array
     */
    public function behaviors()
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

    /**
     * @return array
     */
    public function actions()
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

    /**
     * Page d'accueil du site
     *
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        // Récupération d'une liste éventuellement filtrée selon les critères du moteur de recherche
        $searchModel = new CalendarEntrySearchForm();

        // date de la dernière mise à jour sur des éphémérides
        $lastUpdatedEntry = CalendarEntry::find()->lastUpdated();

        // les éphémérides du jour (avec les tables associées pour limiter le nombre de requêtes)
        $dailyEntries = CalendarEntry::find()->enabled()->byDay(date('Y-m-d'))->orderByDate()->all();

        $previousEntry = $nextEntry = null;
        if (!count($dailyEntries)) {
            $previousEntry = CalendarEntry::find()->lastEntryBeforeCalendarDate(time());
            $nextEntry = CalendarEntry::find()->nextEntryAfterCalendarDate(time());
        }

        $tags = ArrayHelper::map(Tag::find()->orderByLabel()->all(), 'id', 'label');
        $page = WebPage::find()->byCode('Accueil')->one();
        return $this->render('index', compact('lastUpdatedEntry', 'dailyEntries', 'previousEntry', 'nextEntry', 'page', 'searchModel', 'tags'));
    }

    /**
     * Affiche ou traite le formulaire de contact
     *
     * @return string|\yii\web\Response
     */
    public function actionContact()
    {
        $model = new ContactForm();
        $mailTo = h::safeRecipientEmail(Yii::$app->params['adminEmail']);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->contact($mailTo)) {
            // Formulaire soumis sans erreur
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        else {
            // Affichage initial ou ré-affichage après erreur de validation
            $page = WebPage::find()->byCode('Contact')->one();
            return $this->render('contact', compact('model', 'page'));
        }
    }

    /**
     * Affiche la page de présentation du site
     *
     * @return string
     */
    public function actionAbout()
    {
        $page = WebPage::find()->byCode('Presentation')->one();
        return $this->render('about', compact('page'));
    }

    /**
     * @return string
     */
    public function actionOffline()
    {
        return $this->renderPartial('offline');
    }

    /**
     * @return string
     */
    public function actionTest()
    {
        return $this->render('test');
    }

}

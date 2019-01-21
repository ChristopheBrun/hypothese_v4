<?php

namespace app\controllers;

use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\form\CalendarEntrySearchForm;
use app\modules\ephemerides\models\Tag;
use app\modules\user\lib\enums\AppRole;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        //
        // Si l'utilisateur est authentifié, on le redirige sur sa propre page d'accueil,  qui dépend du profil
        //
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->can(AppRole::SUPERADMIN)) {
                return $this->_actionIndexSuperadmin();
            }
        }

        //
        // Page d'accueil publique
        //

        // Récupération d'une liste éventuellement filtrée selon les critères du moteur de recherche
        $searchModel = new CalendarEntrySearchForm();

        // date de la dernière mise à jour sur des éphémérides
        $lastUpdatedEntry = CalendarEntry::find()->lastUpdated();

        // les éphémérides du jour (avec les tables associées pour limiter le nombre de requêtes)
        $dailyEntries = CalendarEntry::find()->enabled()->byDay(date('Y-m-d'))->orderByDate()->with('tags')->all();

        $previousEntry = $nextEntry = null;
        if (!count($dailyEntries)) {
            $previousEntry = CalendarEntry::find()->lastEntryBeforeCalendarDate(time());
            $nextEntry = CalendarEntry::find()->nextEntryAfterCalendarDate(time());
        }

        $tags = ArrayHelper::map(Tag::find()->orderByLabel()->all(), 'id', 'label');
        return $this->render('index', compact('lastUpdatedEntry', 'dailyEntries', 'previousEntry', 'nextEntry', 'searchModel', 'tags'));
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////

    private function _actionIndexSuperadmin()
    {
        return $this->render('indexSuperadmin');
    }


}

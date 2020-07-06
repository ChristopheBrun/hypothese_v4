<?php /** @noinspection PhpUnused */

namespace app\controllers;

use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\form\CalendarEntrySearchForm;
use app\modules\ephemerides\models\Tag;
use Yii;
use yii\db\Exception;
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
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'contact', 'error', 'captcha'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['management'],
                        'allow' => true,
                        'roles' => ['superadmin'],
                    ],
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
     * Page d'accueil du site
     *
     * @return string
     * @throws Exception
     */
    public function actionIndex()
    {
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
     * Page d'administration
     *
     * @return string
     */
    public function actionManagement()
    {
        return $this->render('management');
    }

    /**
     * Formulaire de connexion (utilisateurs anonymes seulement)
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
     * Déconnexion (utilisateurs authentifiés seulement)
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Formulaire de contact
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        $formSubmitted = false;
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            $formSubmitted = true;
        }

        return $this->render('contact', compact('model', 'formSubmitted'));
    }


    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////


}

<?php

namespace app\controllers;

use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\form\CalendarEntrySearchForm;
use app\modules\ephemerides\models\Tag;
use app\modules\hlib\components\actions\ErrorAction;
use app\modules\hlib\lib\exceptions\ModelLoadException;
use app\modules\hlib\lib\exceptions\ModelValidationException;
use app\modules\hlib\lib\exceptions\WarningException;
use app\modules\hlib\lib\Flash;
use Yii;
use Exception;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\ViewAction;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
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
                        'actions' => ['index', 'contact', 'error', 'captcha', 'joke'],
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
                ],
            ],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'joke' => [
                'class' => ViewAction::class,
                'defaultView' => 'joke',
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
     * @throws Exception
     */
    public function actionIndex(): string
    {
        // Récupération d'une liste éventuellement filtrée selon les critères du moteur de recherche
        $searchModel = new CalendarEntrySearchForm();

        // les éphémérides du jour (avec les tables associées pour limiter le nombre de requêtes)
        $dailyEntries = CalendarEntry::find()->enabled()->byDay(date('Y-m-d'))->orderByDate()->with('tags')->all();
        $tags = Tag::find()->orderByLabel()->all();
        return $this->render('index', compact('dailyEntries', 'searchModel', 'tags'));
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
    public function actionLogout(): Response
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
        if (Yii::$app->request->isPost) {
            try {
                if (!$model->load(Yii::$app->request->post())) {
                    throw new ModelLoadException($model, Yii::$app->request->post());
                }

                if (!$model->validate()) {
                    throw new ModelValidationException($model);
                }

                if (!$model->sendMail()) {
                    throw new Exception("Erreur lors de l'envoi du mail");
                }

                $formSubmitted = true;
                Flash::success("Votre message a bien été envoyé");
            } catch (WarningException $x) {
                Flash::warning($x->getMessage());
            } catch (Exception $x) {
                Flash::error("Une erreur est survenue. Veuillez ré-essayer ultérieurement");
            }
        }

        return $this->render('contact', compact('model', 'formSubmitted'));
    }


    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////


}

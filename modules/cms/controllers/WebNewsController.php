<?php

namespace app\modules\cms\controllers;

use app\modules\cms\HCms;
use app\modules\cms\models\BaseNews;
use app\modules\cms\models\forms\WebNewsSearch;
use app\modules\cms\models\Language;
use app\modules\cms\models\WebTag;
use app\modules\hlib\components\actions\RegisterSortRequestAction;
use app\modules\hlib\controllers\BaseController;
use app\modules\hlib\helpers\h;
use app\modules\hlib\HLib;
use app\modules\hlib\tools\ListSorter;
use Yii;
use app\modules\cms\models\WebNews;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * WebNewsController implements the CRUD actions for WebNews model.
 */
class WebNewsController extends BaseController
{
    public function behaviors()
    {
        return [
            'access_admin' => [
                'class' => AccessControl::class,
                'except' => ['post-search', 'display-search-results', 'display-search-results-sort', 'show'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renvoie une instance du modèle identifiée par sa clé primaire.
     * Si cette clé ne correspond à aucun objet, une erreur 404 est lancée.
     *
     * @param string $id
     * @return WebNews
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = WebNews::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Modèle introuvable : #' . $id);
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index-sort' => [
                'class' => RegisterSortRequestAction::class,
                'sessionKeyForSortClause' => WebNews::class . '.index.sort',
                'sessionKeyForPageClause' => WebNews::class . '.index.page',
                'redirectToUrl' => '/cms/web-news/index',
            ],
            'display-search-results-sort' => [
                'class' => RegisterSortRequestAction::class,
                'sessionKeyForSortClause' => WebNews::class . '.display-search-results.sort',
                'sessionKeyForPageClause' => WebNews::class . '.display-search-results.page',
                'redirectToUrl' => '/cms/web-news/display-search-results',
            ],
        ];
    }

    /**
     * Lists all WebNews models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => WebNews::find()->joinWith(['base', 'language'], true)->orderBy('base_news.event_date DESC'),
            ]),
            'emptyBaseNews' => BaseNews::find()->withoutWebNews()->all(),
        ]);
    }

    /**
     * Displays a single WebNews model
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'relatedTags' => WebTag::find()->forWebNews($model->id)->all(),
        ]);
    }

    /**
     * Displays a single WebNews model (frontend)
     *
     * @param integer $id
     * @return mixed
     */
    public function actionShow($id)
    {
        $model = $this->findModel($id);
        /** @noinspection PhpUndefinedFieldInspection */
        $this->layout = $this->module->frontendLayout;

        return $this->render('show', [
            'model' => $model,
            'relatedTags' => WebTag::find()->forWebNews($model->id)->all(),
        ]);
    }

    /**
     * Creates a new WebNews model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WebNews();
        $request = Yii::$app->request;
        $ok = true;
        $baseModel = null;

        if ($request->isPost) {
            // Mise à jour de la WebNews et de la BaseNews associée
            $post = $request->post();
            /** @var BaseNews $baseModel */
            $baseModel = BaseNews::findOne($post['WebNews']['base_id']);

            $ok &= ($baseModel && $baseModel->load($post) && $baseModel->save());
            $ok &= ($model->load($post) && $model->save());
            if (!$ok) {
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                return $this->redirectAfterCreateSuccess($model);
            }
        }
        else {
            $baseModel = new BaseNews();
        }

        // Affichage initial ou ré-affichage en cas d'erreur
        $baseNews = BaseNews::find()->orderBy('event_date DESC')->all();
        $languages = Language::find()->orderBy('iso_639_code')->all();
        return $this->render('create', compact('model', 'baseNews', 'languages', 'baseModel'));
    }

    /**
     * Updates an existing WebNews model.
     * If update is successful, the browser will be redirected to the 'view' page
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $request = Yii::$app->request;
        $ok = true;
        $baseModel = null;

        if ($request->isPost) {
            // Mise à jour de la WebNews et de la BaseNews associée
            $post = $request->post();

            /** @var BaseNews $baseModel */
            if (!($baseModel = BaseNews::findOne($post['WebNews']['base_id']))) {
                $baseModel = new BaseNews();
            }

            $ok &= ($baseModel
                && $baseModel->updateBaseTagsFromRequest($post)
                && $baseModel->load($post)
                && $baseModel->save(true, null, true));

            $ok &= ($model->load($post)
                && $model->save());

            if (!$ok) {
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                Yii::$app->session->setFlash('flash-success', HLib::t('messages', 'Update successful'));
                if ($request->getBodyParam('action') == 'saveAndBackToList') {
                    return $this->redirect(Url::to([$this->getControllerRoute() . '/index', 'page' => 1]));
                }
            }
        }

        // Affichage initial ou ré-affichage en cas d'erreur
        return $this->render('update', [
            'model' => $model,
            'baseNews' => BaseNews::find()->orderBy('event_date DESC')->all(),
            'languages' => Language::find()->orderBy('iso_639_code')->all(),
            'baseModel' => $model->base,
        ]);
    }

    /**
     * Deletes an existing WebNews model.
     * If deletion is successful, the browser will be redirected to the 'index' page
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        return $this->deleteModelAndRedirect($this->findModel($id));
    }

    /**
     * Traitement d'une recherche en frontend et affichage des résultats
     *
     * @return mixed
     */
    public function actionPostSearch()
    {
        $model = new WebNewsSearch();
        /** @var array $request [WebNewsSearch[...], action[submit|clear]] */
        $request = Yii::$app->request->isPost ? Yii::$app->request->post() : Yii::$app->request->get();

        if (isset($request['action']['clear'])) {
            // Suppression des filtres
            $model->deleteFiltersInSession();
            $request = [];
        }

        // Activation des filtres
        $request['status'] = 1;
        if ($model->load($request) && $model->validate()) {
            ArrayHelper::remove($request, '_csrf');
            ArrayHelper::remove($request, 'action');
            $model->storeFiltersInSession($request);
        }
        else {
            Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
        }

        return $this->redirect(Url::to(['/cms/web-news/display-search-results', 'page' => 1]));
    }

    /**
     * Affichage de la liste des objets, avec ou sans filtre selon le contenu de la session
     * (frontend)
     *
     * @param int $page Numéro de la page courante (commence à 1)
     * @return mixed
     */
    public function actionDisplaySearchResults($page)
    {
        // Récupération d'une liste éventuellement filtrée selon les critères du moteur de recherche
        $searchModel = new WebNewsSearch();
        $advancedSearchFilters = $searchModel->retrieveFiltersFromSession();
        $dataProvider = $searchModel->search($advancedSearchFilters);

        // Détermination de l'ordre de tri
        $sortClausesSessionKey = WebNews::class . '.display-search-results.sort';
        $dataProvider->query = ListSorter::updateQuery($dataProvider->query, $sortClausesSessionKey);

        Yii::$app->session->set(WebNews::class . '.index.page', $page);
        $dataProvider->pagination->page = --$page;

        /** @var HCms $module */
        $module = $this->module;
        if (isset($module->frontendLayout)) {
            $this->layout = $module->frontendLayout;
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return $this->render('displaySearchResults', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tags' => ArrayHelper::map(WebTag::find()->byLanguageCode(h::getIso639Code())->orderByLabel()->all(), 'id', 'label'),
            'sortClausesSessionKey' => $sortClausesSessionKey,
        ]);
    }

}

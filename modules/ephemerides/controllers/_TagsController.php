<?php

namespace app\modules\ephemerides\controllers;

use app\modules\hlib\HLib;
use app\modules\hlib\tools\ListSorter;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use app\modules\ephemerides\models\Tag;
use app\modules\ephemerides\models\TagSearchForm;

/**
 * TagsController implements the CRUD actions for Tag model.
 */
class TagsController extends Controller
{
    /**
     *
     */
    public function init()
    {
        $this->layout = 'backend';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access_admin' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['superadmin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Finds the Tag model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     * @return Tag the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tag::findOne($id)) !== null) {
            return $model;
        }
        else {
            throw new NotFoundHttpException(HLib::t('messages', 'The requested page does not exist'));
        }
    }

    /**
     * Affichage de la liste des objets
     * @param int $page Numéro de la page courante (commence à 1)
     *
     * @return mixed
     */
    public function actionIndex($page)
    {
        $searchModel = new TagSearchForm();
        $dataProvider = $searchModel->search($searchModel->retrieveFiltersFromSession());

        // Détermination de l'ordre de tri
        $dataProvider->query = ListSorter::updateQuery($dataProvider->query, Tag::class . '.sort');

        Yii::$app->session->set(Tag::class . '.page', $page);
        $dataProvider->pagination->page = --$page;

        /** @noinspection PhpUndefinedMethodInspection */
        $dataProvider->query->with('calendarEntries');
        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    /**
     * Traitement des demandes de tri / sur les colonnes : on enregistre en session le critère de tri demandé.
     *
     * @param string $orderBy Nom de la colonne sur laquelle on demande un tri. Celui-ci boucle sur 3 états : asc/desc/n-a
     */
    public function actionSort($orderBy)
    {
        ListSorter::updateSession($orderBy, Tag::class . '.sort');
        $currentPage = Yii::$app->session->get(Tag::class . '.page', 1);
        $this->redirect(Url::to(['/tags/index', 'page' => $currentPage]));
    }

    /**
     * Affichage de la page de consultation
     *
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', compact('model'));
    }

    /**
     * Affichage du formulaire de création
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tag();
        if(Yii::$app->request->isPost) {
            // Traitement du formulaire
            if (!($model->load(Yii::$app->request->post()) && $model->save())) {
                // On ré-affiche le formulaire avec ses erreurs
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                // Retour à la liste ou redirection sur la page d'édition, selon le bouton qui a été cliqué
                Yii::$app->session->setFlash('flash-success', HLib::t('messages', 'Create success'));
                $requestedRedirection =
                    Yii::$app->request->getBodyParam('action') == 'saveAndKeepEditing' ?
                        Url::toRoute(['/tags/update', 'id' => $model->id]) :
                        Url::to(['/tags/index', 'page' => 1]);
                return $this->redirect($requestedRedirection);
            }
        }

        // Affichage ou ré-affichage
        return $this->render('create', compact('model'));
    }

    /**
     * Affichage du formulaire de modification
     *
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isPost) {
            // Traitement du formulaire
            if (!($model->load(Yii::$app->request->post()) && $model->save())) {
                // On ré-affiche le formulaire avec ses erreurs
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                Yii::$app->session->setFlash('flash-success', HLib::t('messages', 'Update successful'));
                if(Yii::$app->request->getBodyParam('action') == 'saveAndBackToList') {
                    return $this->redirect(Url::to(['/tags/index', 'page' => 1]));
                }
            }
        }

        // Affichage ou ré-affichage
        return $this->render('update', compact('model'));
    }

    /**
     * Suppression d'un objet
     *
     * @param int $id
     * @return mixed
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('flash-success', HLib::t('messages', 'Delete success'));
        } catch (\Throwable $s) {
            Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'This object is referenced by another object. Deletion failed'));
        }

        return $this->redirect(Url::to(['/tags/index', 'page' => 1]));
    }

    /**
     * Traitement du formulaire de recherche
     */
    public function actionSearch()
    {
        /** @var array $request [TagSearchForm[...], action[submit|clear]] */
        $request = Yii::$app->request->post();
        $model = new TagSearchForm();

        if (isset($request['action']['clear'])) {
            // Suppression des filtres
            $model->deleteFiltersInSession();
        }
        else {
            // Activation des filtres
            if ($model->load($request) && $model->validate()) {
                ArrayHelper::remove($request, '_csrf');
                ArrayHelper::remove($request, 'action');
                $model->storeFiltersInSession($request);
            }
            else {
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
        }

        $this->redirect(Url::to(['/tags/index', 'page' => 1]));
    }

}

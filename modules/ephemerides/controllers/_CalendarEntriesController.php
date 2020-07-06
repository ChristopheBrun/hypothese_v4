<?php

namespace app\modules\ephemerides\controllers;

use app\modules\ephemerides\models\query\CalendarEntryQuery;
use app\modules\ephemerides\models\Tag;
use app\modules\ephemerides\models\CalendarEntry;
use app\modules\hlib\components\actions\RegisterSortRequestAction;
use app\modules\hlib\helpers\hFile;
use app\modules\hlib\helpers\hImage;
use app\modules\hlib\HLib;
use app\modules\ephemerides\models\CalendarEntrySearchForm;
use app\modules\hlib\tools\ListSorter;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * CalendarEntriesController implements the CRUD actions for CalendarEntry model.
 */
class CalendarEntriesController extends Controller
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
                // show & list sont autorisées à tout le monde (frontend)
                // les autres sont réservées aux administrateurs (backend)
                'except' => ['show', 'post-search', 'display-search-results', 'display-search-results-sort'],
                'rules' => [
                    ['allow' => true, 'roles' => ['superadmin']],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index-sort' => [
                'class' => RegisterSortRequestAction::class,
                'sessionKeyForSortClause' => CalendarEntry::class . '.index.sort',
                'sessionKeyForPageClause' => CalendarEntry::class . '.index.page',
                'redirectToUrl' => '/calendar-entries/index',
            ],
            'display-search-results-sort' => [
                'class' => RegisterSortRequestAction::class,
                'sessionKeyForSortClause' => CalendarEntry::class . '.display-search-results.sort',
                'sessionKeyForPageClause' => CalendarEntry::class . '.display-search-results.page',
                'redirectToUrl' => '/calendar-entries/display-search-results',
            ],
        ];
    }

    /**
     * Finds the CalendarEntry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CalendarEntry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CalendarEntry::findOne($id)) !== null) {
            return $model;
        }
        else {
            throw new NotFoundHttpException(HLib::t('messages', 'The requested page does not exist'));
        }
    }

    /**
     * Affichage de la liste des objets, avec ou sans filtre selon le contenu de la session
     *
     * @param int $page Numéro de la page courante (commence à 1)
     * @return mixed
     * @throws Exception
     */
    public function actionIndex($page)
    {
        // Récupération d'une liste éventuellement filtrée selon les critères du moteur de recherche
        $searchModel = new CalendarEntrySearchForm();
        $advancedSearchFilters = $searchModel->retrieveFiltersFromSession();
        $dataProvider = $searchModel->search($advancedSearchFilters);

        if (!$advancedSearchFilters) {
            /** @noinspection PhpParamsInspection */
            $dataProvider->query = $this->retrieveFiltersFromGet($dataProvider->query);
        }

        // Détermination de l'ordre de tri
        $sortClausesSessionKey = CalendarEntry::class . '.index.sort';
        $dataProvider->query = ListSorter::updateQuery($dataProvider->query, $sortClausesSessionKey);
        $dataProvider->query->addOrderBy('updated_at DESC');

        // Mémorisation de la page courante
        Yii::$app->session->set(CalendarEntry::class . '.index.page', $page);
        $dataProvider->pagination->page = --$page;

        /** @noinspection PhpUndefinedMethodInspection */
        $dataProvider->query->with('tags');
        $tags = ArrayHelper::map(Tag::find()->orderByLabel()->all(), 'id', 'label');
        return $this->render('index', compact('searchModel', 'dataProvider', 'tags', 'sortClausesSessionKey'));
    }

    /**
     * Traitement du formulaire de recherche (backend)
     * On enregistre les filtres en session.
     * L'affichage filtré est géré après redirection dans executeIndex()
     *
     * @param null|string $searchCode
     */
    public function actionRegisterFilters($searchCode = null)
    {
        if (Yii::$app->request->isPost) {
            // critères de filtre fournis par le formulaire du moteur de recherche
            $this->registerAdvancedSearch();
        }
        else {
            // recherche simplifiée (bouton 'J&J+1' par exemple)
            $this->registerSearchCode($searchCode);
        }

        $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Traitement d'une recherche en frontend et affichage des résultats
     *
     * @return mixed
     */
    public function actionPostSearch()
    {
        $model = new CalendarEntrySearchForm();
        /** @var array $request [CalendarEntrySearchForm[...], action[submit|clear]] */
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

        return $this->redirect(Url::to(['calendar-entries/display-search-results', 'page' => 1]));
    }

    /**
     * Affichage de la liste des objets, avec ou sans filtre selon le contenu de la session
     *
     * @param int $page Numéro de la page courante (commence à 1)
     * @return mixed
     */
    public function actionDisplaySearchResults($page)
    {
        // Récupération d'une liste éventuellement filtrée selon les critères du moteur de recherche
        $searchModel = new CalendarEntrySearchForm();
        $advancedSearchFilters = $searchModel->retrieveFiltersFromSession();
        $dataProvider = $searchModel->search($advancedSearchFilters);

        // Détermination de l'ordre de tri
        $sortClausesSessionKey = CalendarEntry::class . '.display-search-results.sort';
        $dataProvider->query = ListSorter::updateQuery($dataProvider->query, $sortClausesSessionKey);

        Yii::$app->session->set(CalendarEntry::class . '.index.page', $page);
        $dataProvider->pagination->page = --$page;

        $this->layout = 'frontend';
        /** @noinspection PhpUndefinedMethodInspection */
        $dataProvider->query->with('tags');
        $tags = ArrayHelper::map(Tag::find()->orderByLabel()->all(), 'id', 'label');
        return $this->render('displaySearchResults', compact('searchModel', 'dataProvider', 'tags', 'sortClausesSessionKey'));
    }

    /**
     * Fiche de l'objet (backend)
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
     * Affiche l'éphéméride complète dans une page dédiée (frontend)
     *
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionShow($id)
    {
        /** @var CalendarEntry $model */
        if (!($model = CalendarEntry::findOne($id))) {
            throw new NotFoundHttpException(HLib::t('messages', 'The requested page does not exist'));
        }

        if (!$model->enabled) {
            throw new NotFoundHttpException(HLib::t('messages', 'The requested page does not exist'));
        }

        $previousEntryInChronology = CalendarEntry::find()->previousInChronology($model)->enabled()->limit(1)->one();
        $previousEntryInCalendar = CalendarEntry::find()->lastEntryBeforeCalendarDate(time());
        $nextEntryInChronology = CalendarEntry::find()->nextInChronology($model)->enabled()->limit(1)->one();
        $nextEntryInCalendar = CalendarEntry::find()->nextEntryAfterCalendarDate(time());

        $this->layout = 'frontend';
        return $this->render('show', compact('model',
                'previousEntryInChronology', 'nextEntryInChronology', 'previousEntryInCalendar', 'nextEntryInCalendar')
        );
    }

    /**
     * Affichage du formulaire de création
     *
     * @return mixed
     * @throws \yii\db\Exception
     * @throws \Exception
     */
    public function actionCreate()
    {
        $model = new CalendarEntry();
        if (Yii::$app->request->isPost) {
            // Traitement du formulaire
            $oldAttributes = $model->getAttributes();
            $post = Yii::$app->request->post();
            if (!($this->processTags($post) && $model->load($post) && $model->save(true, null, true))) {
                // On ré-affiche le formulaire avec ses erreurs
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                // le modèle est valide, on entame le traitement de l'image (téléchargement, suppression, renommage)
                if (!$this->processModelImage($model, $post, $oldAttributes, UploadedFile::getInstance($model, 'uploadedImage'))) {
                    // On ré-affiche le formulaire avec ses erreurs
                    Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'The entry has been created but there are errors with the image'));
                }
                else {
                    // Retour à la liste ou redirection sur la page d'édition, selon le bouton qui a été cliqué
                    Yii::$app->session->setFlash('flash-success', HLib::t('messages', 'Create success'));
                }

                $requestedRedirection =
                    Yii::$app->request->getBodyParam('action') == 'saveAndKeepEditing' ?
                        Url::toRoute(['/calendar-entries/update', 'id' => $model->id]) :
                        Url::to(['/calendar-entries/index', 'page' => 1]);
                return $this->redirect($requestedRedirection);
            }
        }

        // Affichage ou ré-affichage
        $tags = ArrayHelper::map(Tag::find()->orderByLabel()->asArray()->all(), 'id', 'label');
        return $this->render('create', compact('model', 'tags', 'articles'));
    }

    /**
     * Affichage du formulaire de modification
     *
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     * @throws \Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->isPost) {
            // Traitement du formulaire
            $oldAttributes = $model->getAttributes();
            $post = Yii::$app->request->post();
            if (!($this->processTags($post) && $model->load($post) && $model->save(true, null, true))) {
                // On ré-affiche le formulaire avec ses erreurs
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                // le modèle est valide, on entame le traitement de l'image (téléchargement, suppression, renommage)
                if (!$this->processModelImage($model, $post, $oldAttributes, UploadedFile::getInstance($model, 'uploadedImage'))) {
                    // On ré-affiche le formulaire avec ses erreurs
                    Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'The entry has been updated but there are errors with the image'));
                }
                else {
                    Yii::$app->session->setFlash('flash-success', HLib::t('messages', 'Update successful'));
                    if (Yii::$app->request->getBodyParam('action') == 'saveAndBackToList') {
                        return $this->redirect(Url::to(['/calendar-entries/index', 'page' => 1]));
                    }
                }

                $model->refresh();
            }
        }

        // Affichage ou ré-affichage
        $tags = ArrayHelper::map(Tag::find()->orderByLabel()->asArray()->all(), 'id', 'label');
        return $this->render('update', compact('model', 'tags', 'articles'));
    }

    /**
     * Suppression d'un objet
     *
     * @param int $id
     * @return mixed
     * @throws \Exception
     * @throws \Exception
     * @throws Throwable
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('flash-success', HLib::t('messages', 'Delete success'));
        } catch (Exception $x) {
            Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'This object is referenced by another object. Deletion failed'));
        }

        $currentPage = Yii::$app->session->get(CalendarEntry::class . '.index.page');
        return $this->redirect(Url::to(['/calendar-entries/index', 'page' => $currentPage]));
    }

//    /**
//     * Traitement de l'image du modèle : suppression, renommage, téléchargement depuis le formulaire
//     * Puisque le nom des images dépend du titre du modèle, cette méthode doit être appelée après le save() pour éviter les
//     * désynchronisations. Un nouveau save() peut être appelé en interne si nécessaire.
//     *
//     * @param CalendarEntry $model
//     * @param array         $request
//     * @param array         $oldAttributes
//     * @param UploadedFile  $uploadedFile Vaut NULL si aucune image n'est téléchargée
//     * @return bool
//     * @throws \Exception
//     */
//    private function processModelImage(CalendarEntry $model, array $request, array $oldAttributes, UploadedFile $uploadedFile = null)
//    {
//        $saveModel = false;
//
//        // Si le modèle a déjà une image, on vérifie s'il faut la supprimer ou la renommer
//        if ($model->hasImage()) {
//            if (ArrayHelper::getValue($request, 'CalendarEntry.deleteImage')) {
//                // Si la case "supprimer est cochée", on supprime l'ancienne image & les vignettes
//                $model->deleteImageFiles();
//                $saveModel = true;
//            }
//            elseif ($oldAttributes['title'] != $model->title || $oldAttributes['event_date'] != $model->event_date) {
//                // Si le titre du modèle a changé, il faut renommer les images de l'objet
//                $model->image = $model->computeImageName(hFile::getExtension($model->image));
//                $model->resetImagesNames();
//                $saveModel = true;
//            }
//        }
//
//        // Traitement de l'image téléchargée
//        if ($uploadedFile) {
//            // Une nouvelle image a été téléchargée : on la déplace dans le répertoire des images de CalendarEntry
//            $imagesDirectoryPath = $model->getImagesDirectoryPath(true);
//            $file = $imagesDirectoryPath . '/' . $model->computeImageName($uploadedFile->extension);
//            if (!$uploadedFile->saveAs($file)) {
//                return false;
//            }
//
//            // On enregistre l'image sous un format standard (jpg par défaut) et à une taille appropriée
//            // S'il y avait déjà une image pour ce modèle, elles sera écrasée
//            $extension = Yii::$app->params['calendarEntry']['images']['extension'];
//            if ($uploadedFile->extension != $extension) {
//                // Le type de l'image reçue n'est pas le bon : on la ré-encode...
//                $image = hImage::configure()->make($file)->encode($extension, 100);
//                $reEncodedFile = $imagesDirectoryPath . '/' . $model->computeImageName($extension);
//                $image->save($reEncodedFile)->destroy();
//                // ... et on efface le fichier avec la mauvaise extension
//                hFile::delete($file);
//                $file = $reEncodedFile;
//            }
//
//            // Mise à jour du modèle et des vignettes
//            $model->image = basename($file);
//            $model->resizeOriginalImage();
//            $model->setThumbnails();
//            $saveModel = true;
//        }
//
//        if ($saveModel) {
//            return $model->save(false);
//        }
//
//        return true;
//    }

    /**
     * Contrôle la liste de tags déclarés dans $request[CalendarEntry][tags] et crée s'il le faut de nouveaux tags dans la base.
     * Renvoie le tableau après avoir remplacé les libellés des nouveaux tags par leur identifiant.
     *
     * @param array $request
     * @return boolean
     */
    private function processTags(array &$request)
    {
        $currentTags = ArrayHelper::getColumn(Tag::find()->all(), 'id');
        $updatedTags = ArrayHelper::getValue($request, 'CalendarEntry.tags', []);
        if ($updatedTags === "") {
            $updatedTags = [];
        }

        foreach ($updatedTags as $i => $value) {
            if (!in_array($value, $currentTags)) {
                // $value n'est pas l'identifiant d'un tag déjà connu en base mais le libellé d'un nouveau tag
                $tag = new Tag(['label' => $value]);
                if (!$tag->save()) {
                    return false;
                }

                $updatedTags[$i] = $tag->id;
            }
        }

        $request['CalendarEntry']['tags'] = $updatedTags;
        return true;
    }

    /**
     * Enregistre les filtres en session.
     *
     * @internal L'affichage filtré est fait dans executeIndex().
     */
    private function registerAdvancedSearch()
    {
        /** @var array $request [CalendarEntrySearchForm[...], action[submit|clear]] */
        $request = Yii::$app->request->post();
        $model = new CalendarEntrySearchForm();
        Yii::$app->session->remove(self::class . 'searchCode');

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
    }

    /**
     * Enregistre les filtres en session.
     *
     * @internal L'affichage filtré est fait dans executeIndex()
     * @param string $searchCode
     */
    private function registerSearchCode($searchCode)
    {
        (new CalendarEntrySearchForm())->deleteFiltersInSession();
        if ($searchCode != 'all') {
            Yii::$app->session->set(self::class . 'searchCode', $searchCode);
        }
        else {
            Yii::$app->session->remove(self::class . 'searchCode');
        }
    }

    /**
     * Mise à jour de $query avec les critères de recherche simplifiés (issus d'un lien/bouton et non du formulaire)
     *
     * @param CalendarEntryQuery $query
     * @return CalendarEntryQuery
     * @throws Exception
     */
    private function retrieveFiltersFromGet(CalendarEntryQuery $query)
    {
        $searchCode = Yii::$app->session->get(self::class . 'searchCode');
        switch ($searchCode) {
            case '' :
                break;
            case 'today' :
                $query->andWhere('DAY(event_date) = :day AND MONTH(event_date) = :month', [
                    'day' => date('d'),
                    'month' => date('m'),
                ]);
                break;
            case 'tomorrow' :
                $query->andWhere('DAY(event_date) = :day AND MONTH(event_date) = :month', [
                    'day' => date('d', time() + 24 * 3600),
                    'month' => date('m', time() + 24 * 3600),
                ]);
                break;
            default :
                throw new Exception(Yii::t('messages', 'Unknown search criteria') . ' : ' . $searchCode);
        }

        return $query;
    }

}

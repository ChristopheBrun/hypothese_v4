<?php /** @noinspection PhpUnused */

namespace app\modules\ephemerides\controllers;

use app\modules\ephemerides\EphemeridesModule;
use app\modules\ephemerides\helpers\CalendarEntryHelper;
use app\modules\ephemerides\models\query\CalendarEntryTagQuery;
use app\modules\ephemerides\models\Tag;
use app\modules\hlib\helpers\hFile;
use app\modules\hlib\helpers\hImage;
use app\modules\hlib\HLib;
use app\modules\hlib\lib\Flash;
use app\modules\user\lib\enums\AppRole;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Exception;
use Throwable;
use Yii;
use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\form\CalendarEntrySearchForm;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * CalendarEntryController implements the CRUD actions for CalendarEntry model.
 */
class CalendarEntryController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [AppRole::SUPERADMIN],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['?'],
                        'actions' => ['show'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Liste des éphémérides
     *
     * @return string
     * @throws Exception
     */
    public function actionIndex(): string
    {
        $searchModel = new CalendarEntrySearchForm();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tags' => Tag::find()->orderByLabel()->all(),
            'dateWithNoEntries' => CalendarEntryHelper::findNextDateWithNoEntries(),
        ]);
    }

    /**
     * Liste des éphémérides du jour J
     *
     * @return string|Response
     * @throws Exception
     */
    public function actionIndexD(): Response|string
    {
        // S'il y a un filtre demandé manuellement sur la date, il faut revenir à l'index par défaut
        if (ArrayHelper::getValue(Yii::$app->request->queryParams, 'event_date')) {
            return $this->redirect(array_merge(['index'], Yii::$app->request->queryParams));
        }

        // Si aucun filtre n'est demandé manuellement sur la date, on peut rester sur cette page et
        // traiter les autres filtres en plus du filtre local 'J'
        $searchModel = new CalendarEntrySearchForm();

        $dateParams = [];
        $date = new DateTimeImmutable();
        /** @noinspection DuplicatedCode */
        $dateParams[$searchModel->formName()]['dateParams'][] = ['day' => $date->format('d'), 'month' => $date->format('m')];
        $params = ArrayHelper::merge(Yii::$app->request->queryParams, $dateParams);

        $dataProvider = $searchModel->search($params);
        $tags = Tag::find()->orderByLabel()->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tags' => $tags,
            'filter' => EphemeridesModule::t('labels', 'D'),
            'dateWithNoEntries' => CalendarEntryHelper::findNextDateWithNoEntries(),
        ]);
    }

    /**
     * Liste des éphémérides du jour J+1
     *
     * @return string|Response
     * @throws Exception
     */
    public function actionIndexD1(): Response|string
    {
        // S'il y a un filtre demandé manuellement sur la date, il faut revenir à l'index par défaut
        if (ArrayHelper::getValue(Yii::$app->request->queryParams, 'event_date')) {
            return $this->redirect(array_merge(['index'], Yii::$app->request->queryParams));
        }

        // Si aucun filtre n'est demandé manuellement sur la date, on peut rester sur cette page et
        // traiter les autres filtres en plus du filtre local 'J'
        $searchModel = new CalendarEntrySearchForm();

        $dateParams = [];
        $date = new DateTime();
        $date->add(new DateInterval('P1D'));
        /** @noinspection DuplicatedCode */
        $dateParams[$searchModel->formName()]['dateParams'][] = ['day' => $date->format('d'), 'month' => $date->format('m')];
        $params = ArrayHelper::merge(Yii::$app->request->queryParams, $dateParams);

        $dataProvider = $searchModel->search($params);
        $tags = Tag::find()->orderByLabel()->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tags' => $tags,
            'filter' => EphemeridesModule::t('labels', 'D+1'),
            'dateWithNoEntries' => CalendarEntryHelper::findNextDateWithNoEntries(),
        ]);
    }

    /**
     * Liste des éphémérides compris entre J et J+2
     *
     * @return Response|string
     * @throws Exception
     */
    public function actionIndexD2(): Response|string
    {
        // S'il y a un filtre demandé manuellement sur la date, il faut revenir à l'index par défaut
        if (ArrayHelper::getValue(Yii::$app->request->queryParams, 'event_date')) {
            return $this->redirect(array_merge(['index'], Yii::$app->request->queryParams));
        }

        // Si aucun filtre n'est demandé manuellement sur la date, on peut rester sur cette page et
        // traiter les autres filtres en plus du filtre local 'J à J+2'
        $searchModel = new CalendarEntrySearchForm();

        $dateParams = [];
        $date = new DateTime();
        for ($i = 0; $i < 3; ++$i) {
            $dateParams[$searchModel->formName()]['dateParams'][] = ['day' => $date->format('d'), 'month' => $date->format('m')];
            $date->add(new DateInterval('P1D'));
        }
        $params = ArrayHelper::merge(Yii::$app->request->queryParams, $dateParams);

        $dataProvider = $searchModel->search($params);
        $tags = Tag::find()->orderByLabel()->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tags' => $tags,
            'filter' => EphemeridesModule::t('labels', 'D to D+2'),
            'dateWithNoEntries' => CalendarEntryHelper::findNextDateWithNoEntries(),
        ]);
    }

    /**
     * Fiche en lecture seule
     *
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Affiche l'éphéméride dans un widget telle qu'elle est censée être vue sur le frontend
     *
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionShow(int $id): string
    {
        return $this->render('show', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CalendarEntry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return Response|string
     */
    public function actionCreate(): Response|string
    {
        $model = new CalendarEntry();

        if (Yii::$app->request->isPost && $this->processForm($model, Yii::$app->request->post())) {
            return $this->redirect(Url::to(['view', 'id' => $model->id], true));
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CalendarEntry model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return Response|string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost && $this->processForm($model, Yii::$app->request->post())) {
            return $this->redirect(Url::to(['view', 'id' => $model->id], true));
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param CalendarEntry $model
     * @param array $data
     * @return bool
     */
    private function processForm(CalendarEntry $model, array $data): bool
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $oldAttributes = $model->getAttributes();
            $create = $model->isNewRecord;

            if (!$this->prepareTags($data)) {
                throw new Exception('!$this->prepareTags()');
            }

            if (!$model->load($data)) {
                throw new Exception('!$model->load()');
            }

            if (!$model->validate()) {
                throw new Exception('!$model->validate()');
            }

            if (!$model->save(false)) {
                throw new Exception('!$model->save()');
            }

            if (!$this->processModelImage($model, $data, $oldAttributes, UploadedFile::getInstance($model, 'uploadedImage'))) {
                throw new Exception('!$this->processModelImage()');
            }

            if (!$this->processTags($model, $data)) {
                throw new Exception('!$this->processTags()');
            }

            $transaction->commit();
            $flashMsg = $create ? HLib::t('messages', "Create success") : HLib::t('messages', "Update success");
            Flash::success($flashMsg);
        } catch (Exception $x) {
            Yii::error($x->getMessage());
            Flash::error("Erreur sur " . __METHOD__);
            $transaction->rollBack();
            return false;
        }

        return true;
    }

    /**
     * Traitement de l'image du modèle : suppression, renommage, téléchargement depuis le formulaire
     * Puisque le nom des images dépend du titre du modèle, cette méthode doit être appelée après le save() pour éviter les
     * désynchronisations. Un nouveau save() peut être appelé en interne si nécessaire.
     *
     * @param CalendarEntry $model
     * @param array $data
     * @param array $oldAttributes
     * @param UploadedFile|null $uploadedFile Vaut NULL si aucune image n'est téléchargée
     * @return bool
     * @throws Exception
     */
    private function processModelImage(CalendarEntry $model, array $data, array $oldAttributes, UploadedFile $uploadedFile = null): bool
    {
        $saveModel = false;

        // Si le modèle a déjà une image, on vérifie s'il faut la supprimer ou la renommer
        if ($model->hasImage()) {
            if (ArrayHelper::getValue($data, 'CalendarEntry.deleteImage')) {
                // Si la case "supprimer est cochée", on supprime l'ancienne image & les vignettes
                $model->deleteImageFiles();
                $saveModel = true;
            } elseif ($oldAttributes['title'] != $model->title || $oldAttributes['event_date'] != $model->event_date) {
                // Si le titre du modèle a changé, il faut renommer les images de l'objet
                $model->image = $model->computeImageName(hFile::getExtension($model->image));
                $model->resetImagesNames();
                $saveModel = true;
            }
        }

        // Traitement de l'image téléchargée
        if ($uploadedFile) {
            // Une nouvelle image a été téléchargée : on la déplace dans le répertoire des images de CalendarEntry
            $imagesDirectoryPath = $model->getImagesDirectoryPath(true);
            $file = $imagesDirectoryPath . '/' . $model->computeImageName($uploadedFile->extension);
            if (!$uploadedFile->saveAs($file)) {
                return false;
            }

            // On enregistre l'image sous un format standard (jpg par défaut) et à une taille appropriée
            // S'il y avait déjà une image pour ce modèle, elles sera écrasée
            /** @var EphemeridesModule $module */
            $module = EphemeridesModule::getInstance();
            $extension = $module->images['extension'];
            if ($uploadedFile->extension != $extension) {
                // Le type de l'image reçue n'est pas le bon : on la ré-encode au bon format...
                $image = hImage::configure()->make($file)->encode($extension, 100);
                $reEncodedFile = $imagesDirectoryPath . '/' . $model->computeImageName($extension);
                $image->save($reEncodedFile)->destroy();
                // ... et on efface le fichier avec la mauvaise extension
                hFile::delete($file);
                $file = $reEncodedFile;
            }

            // Mise à jour du modèle et des vignettes
            $model->image = basename($file);
            $model->resizeOriginalImage();
            $model->setThumbnails();
            $saveModel = true;
        }

        if ($saveModel) {
            return $model->save(false);
        }

        return true;
    }

    /**
     * Contrôle la liste de tags déclarés dans $request[CalendarEntry][tags] et crée s'il le faut de nouveaux tags dans la base.
     * Renvoie le tableau après avoir remplacé les libellés des nouveaux tags par leur identifiant.
     *
     * @param array $data
     * @return boolean
     * @throws Exception
     */
    private function prepareTags(array &$data): bool
    {
        $currentTags = ArrayHelper::getColumn(Tag::find()->all(), 'id');
        $updatedTags = ArrayHelper::getValue($data, 'CalendarEntry.tags', []);
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

        $data['CalendarEntry']['tags'] = $updatedTags;
        return true;
    }

    /**
     * Met à jour la liste des catégories associées à $model
     *
     * @param CalendarEntry $model
     * @param array $data
     * @return bool
     * @throws \yii\db\Exception
     * @throws Exception
     */
    private function processTags(CalendarEntry $model, array $data): bool
    {
        $updatedTagsIds = ArrayHelper::getValue($data, 'CalendarEntry.tags', []);
        return CalendarEntryTagQuery::updateTagsForCalendarEntry($model->getTagsIds(), $updatedTagsIds, $model->id);
    }

    /**
     * Deletes an existing CalendarEntry model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return Response
     */
    public function actionDelete(int $id): Response
    {
        try {
            $model = $this->findModel($id);
            $model->deleteImageFiles();
            if (!$model->delete()) {
                Flash::error(HLib::t('messages', 'Delete error'));
                throw new Exception('aaaaa');
            }

            $model->deleteImageFiles();
            Flash::success(HLib::t('messages', 'Delete success'));
        } catch (Throwable) {
            Flash::warning(HLib::t('messages', 'This object is referenced by another object. Deletion failed'));
        }

        $currentPage = Yii::$app->session->get(CalendarEntry::class . '.index.page');
        $referrer = Yii::$app->request->getReferrer();
        $redirectTo = preg_match("/id=$id$/", $referrer) ?
            Url::to(['calendar-entry/index', 'page' => $currentPage]) : $referrer;
        return $this->redirect($redirectTo);
    }

    /**
     * Finds the CalendarEntry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return CalendarEntry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): CalendarEntry
    {
        if (($model = CalendarEntry::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('labels', 'The requested page does not exist.'));
    }
}

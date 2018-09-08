<?php

namespace app\modules\cms\controllers;

use app\modules\cms\models\BaseTag;
use app\modules\cms\models\Language;
use app\modules\hlib\controllers\BaseController;
use app\modules\hlib\HLib;
use Yii;
use app\modules\cms\models\WebTag;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * WebTagsController implements the CRUD actions for WebTag model.
 */
class WebTagsController extends BaseController
{
    public function behaviors()
    {
        return [
            'access_admin' => [
                'class' => AccessControl::class,
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
     * @return WebTag
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = WebTag::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Modèle introuvable : #' . $id);
    }

    /**
     * Lists all WebTag models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => WebTag::find()->joinWith(['base', 'language'], true)->orderBy('base_tags.code'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'emptyBaseTags' => BaseTag::find()->withoutWebTags()->asArray()->all(),
        ]);
    }

    /**
     * Displays a single WebTag model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new WebTag model.
     *
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $model = new WebTag();
        $baseModel = null;

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Mise à jour du WebTag et du BaseTag associé
                if (!($baseModel = BaseTag::findOne($post['WebTag']['base_id']))) {
                    // Création du BaseTag à la volée & mise à jour de base_id dans $post
                    if ($post['BaseTag']['code']) {
                        $baseModel = new BaseTag();
                        if (!($baseModel->load($post) && $baseModel->save())) {
                            throw new Exception("Erreur à la création du BaseTag");
                        }

                        $post['WebTag']['base_id'] = $baseModel->id;
                    }
                    else {
                        $msg = "Il faut choisir ou créer un BaseTag";
                        Yii::$app->session->addFlash('flash-danger', $msg);
                        throw new Exception($msg);
                    }
                }

                if (!($model->load($post) && $model->save())) {
                    throw new Exception("Erreur à la création du WebTag");
                }

                $transaction->commit();
                return $this->redirectAfterCreateSuccess($model);
            } catch (Exception $x) {
                $transaction->rollBack();
                Yii::$app->session->addFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
        }

        if (!$baseModel) {
            $baseModel = new BaseTag();
        }

        // Affichage initial ou ré-affichage en cas d'erreur
        $baseTags = BaseTag::find()->orderBy('code')->all();
        $languages = Language::find()->orderBy('iso_639_code')->all();
        return $this->render('create', compact('model', 'baseTags', 'languages', 'baseModel'));
    }

    /**
     * Updates an existing WebTag model.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $request = Yii::$app->request;
        $ok = true;
        $baseModel = null;

        if ($request->isPost) {
            // Mise à jour du WebTag et du BaseTag associé
            /** @var BaseTag $baseModel */
            if (!($baseModel = BaseTag::findOne($request->post()['WebTag']['base_id']))) {
                $baseModel = new BaseTag();
            }

            $ok &= ($baseModel && $baseModel->load($request->post()) && $baseModel->save());
            $ok &= ($model->load($request->post()) && $model->save());
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
        else {
            $baseModel = $model->base;
        }

        // Affichage initial ou ré-affichage en cas d'erreur
        $baseTags = BaseTag::find()->orderBy('code')->all();
        $languages = Language::find()->orderBy('iso_639_code')->all();
        return $this->render('update', compact('model', 'baseTags', 'languages', 'baseModel'));
    }

    /**
     * Deletes an existing WebTag model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        return $this->deleteModelAndRedirect($this->findModel($id));
    }

}

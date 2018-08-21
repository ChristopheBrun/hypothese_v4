<?php

namespace app\modules\cms\controllers;

use app\modules\cms\widgets\BaseTagForm;
use app\modules\hlib\controllers\BaseController;
use app\modules\hlib\HLib;
use Yii;
use app\modules\cms\models\BaseTag;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * BaseTagsController implements the CRUD actions for BaseTag model.
 */
class BaseTagsController extends BaseController
{
    public function behaviors()
    {
        return [
        ];
    }

    /**
     * Displays a single BaseTag model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BaseTag model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BaseTag();
        if(Yii::$app->request->isPost) {
            if (!($model->load(Yii::$app->request->post()) && $model->save())) {
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                return $this->redirect(Url::to(['/cms/web-tags/index']));
            }
        }

        // Affichage ou ré-affichage
        return $this->render('create', compact('model'));
    }

    /**
     * Updates an existing BaseTag model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isPost) {
            if (!($model->load(Yii::$app->request->post()) && $model->save())) {
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                Yii::$app->session->setFlash('flash-success', HLib::t('messages', 'Update successful'));
                return $this->redirect(Url::to(['/cms/web-tags/index']));
            }
        }

        // Affichage ou ré-affichage
        return $this->render('update', compact('model'));
    }

    /**
     * Renvoie le code HTML du formulaire associée à la BasePage d'identifiant $id
     *
     * @param int $id
     * @return mixed
     */
    public function actionGetForm($id)
    {
        $model = $this->findModel($id);
        return BaseTagForm::widget(['model' => $model, 'asNestedForm' => true]);
    }

    /**
     * Suppression d'un objet
     *
     * @param int $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        return $this->deleteModelAndRedirect($this->findModel($id), Url::to(['/cms/web-tags/index']));
    }

    /**
     * Finds the BaseTag model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BaseTag the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BaseTag::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Modèle introuvable : #' . $id);
    }
}

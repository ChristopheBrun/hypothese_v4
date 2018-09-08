<?php

namespace app\modules\cms\controllers;

use app\modules\cms\widgets\BaseNewsForm;
use app\modules\hlib\controllers\BaseController;
use app\modules\hlib\HLib;
use Yii;
use app\modules\cms\models\BaseNews;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * BaseNewsController implements the CRUD actions for BaseNews model.
 */
class BaseNewsController extends BaseController
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
     * @return BaseNews
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = BaseNews::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Modèle introuvable : #' . $id);
    }


    /**
     * Displays a single BaseNews model.
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
     * Creates a new BaseNews model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $model = new BaseNews();
        if(Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if (!($model->updateBaseTagsFromRequest($post) && $model->load($post) && $model->save())) {
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                return $this->redirect(Url::to(['/cms/web-news/index']));
            }
        }

        // Affichage initial ou ré-affichage en cas d'erreur
        return $this->render('create', compact('model'));
    }

    /**
     * Updates an existing BaseNews model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if (!($model->updateBaseTagsFromRequest($post) && $model->load($post) && $model->save())) {
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                return $this->redirect(Url::to(['/cms/web-news/index']));
            }
        }

        // Affichage initial ou ré-affichage en cas d'erreur
        return $this->render('update', compact('model'));
    }

    /**
     * Renvoie le code HTML du formulaire associée à la BasePage d'identifiant $id
     *
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionGetForm($id)
    {
        $model = $this->findModel($id);
        return BaseNewsForm::widget(['model' => $model, 'asNestedForm' => true]);
    }

    /**
     * Deletes an existing BaseNews model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        return $this->deleteModelAndRedirect($this->findModel($id), Url::to(['/cms/web-news/index']));
    }

}

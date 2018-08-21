<?php

namespace app\modules\cms\controllers;

use app\modules\cms\widgets\BaseTextForm;
use app\modules\hlib\controllers\BaseController;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\modules\cms\models\BaseText;
use app\modules\hlib\HLib;

/**
 * BaseTextsController implements the CRUD actions for BaseText model.
 */
class BaseTextsController extends BaseController
{
    /**
     * @return array
     */
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
     * @return BaseText
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = BaseText::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Modèle introuvable : #' . $id);
    }

    /**
     * Affichage de la page de consultation
     *
     * @param string $id
     * @return mixed
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
        $model = new BaseText();
        if(Yii::$app->request->isPost) {
            if (!($model->load(Yii::$app->request->post()) && $model->save())) {
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                return $this->redirect(Url::to(['/cms/web-texts/index']));
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
                return $this->redirect(Url::to(['/cms/web-texts/index']));
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
        return BaseTextForm::widget(['model' => $model, 'asNestedForm' => true]);
    }

    /**
     * Suppression d'un objet
     *
     * @param int $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        return $this->deleteModelAndRedirect($this->findModel($id), Url::to(['/cms/web-texts/index']));
    }

}

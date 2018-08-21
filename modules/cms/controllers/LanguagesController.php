<?php

namespace app\modules\cms\controllers;

use Yii;
use yii\db\IntegrityException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

use app\modules\cms\models\Language;
use app\modules\hlib\HLib;


/**
 * LanguagesController implements the CRUD actions for Language model.
 */
class LanguagesController extends Controller
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
     * Finds the Language model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     * @return Language the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Language::findOne($id)) !== null) {
            return $model;
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Affichage de la liste des objets
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Language::find()->orderBy('name'),
        ]);

        return $this->render('index', compact('dataProvider'));
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
        $model = new Language();
        if(Yii::$app->request->isPost) {
            // Traitement du formulaire
            if (!($model->load(Yii::$app->request->post()) && $model->save())) {
                // On ré-affiche le formulaire avec ses erreurs
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                // Retour à la liste ou redirection sur la page d'édition, selon le bouton qui a été cliqué
                Yii::$app->session->setFlash('flash-success', HLib::t('messages', 'Create successful'));
                $requestedRedirection =
                    Yii::$app->request->getBodyParam('action') == 'saveAndKeepEditing' ?
                        Url::toRoute(['/cms/languages/update', 'id' => $model->id]) :
                        Url::toRoute('/cms/languages/index');
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
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isPost) {
            // Traitement du formulaire
            if(!($model->load(Yii::$app->request->post()) && $model->save())) {
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                Yii::$app->session->setFlash('flash-success', HLib::t('messages', 'Update successful'));
                if(Yii::$app->request->getBodyParam('action') == 'saveAndBackToList') {
                    return $this->redirect(Url::to(['/cls/languages/index', 'page' => 1]));
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
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('flash-success', HLib::t('messages', 'Delete successful'));
        }
        catch(IntegrityException $s) {
            Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'This object is referenced by another object. Deletion failed'));
        }

        return $this->redirect(Url::toRoute('/cms/languages/index'));
    }

}

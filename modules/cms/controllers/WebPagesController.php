<?php

namespace app\modules\cms\controllers;

use app\modules\cms\models\BasePage;
use app\modules\cms\models\Language;
use app\modules\cms\models\WebText;
use app\modules\hlib\controllers\BaseController;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

use app\modules\cms\models\WebPage;
use yii\data\ActiveDataProvider;

use app\modules\hlib\HLib;

/**
 * WebPagesController implements the CRUD actions for WebPage model.
 */
class WebPagesController extends BaseController
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
     * @return WebPage
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = WebPage::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Modèle introuvable : #' . $id);
    }

    /**
     * Affichage de la liste des objets
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => WebPage::find()->joinWith(['base', 'language'], true)->orderBy('base_pages.code'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'emptyBasePages' => BasePage::find()->withoutWebPages()->asArray()->all(),
        ]);
    }

    /**
     * Affichage de la page de consultation
     *
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'relatedTexts' => WebText::find()->byWebPage($model)->all(),
        ]);
    }

    /**
     * Affichage du formulaire de création
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WebPage();
        $request = Yii::$app->request;
        $ok = true;
        $baseModel = null;

        if ($request->isPost) {
            // Mise à jour de la WebPage et de la BasePage associée
            /** @var BasePage $baseModel */
            // @todo mettre ça dans une transaction
            $baseModel = BasePage::findOne($request->post()['WebPage']['base_id']);

            $ok &= ($baseModel && $baseModel->load($request->post()) && $baseModel->save());
            $ok &= ($model->load($request->post()) && $model->save());
            if (!$ok) {
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                return $this->redirectAfterCreateSuccess($model);
            }
        }
        else {
            $baseModel = new BasePage();
        }

        // Affichage ou ré-affichage
        $basePages = BasePage::find()->orderBy('code ASC')->all();
        $languages = Language::find()->orderBy('name')->all();
        return $this->render('create', compact('model', 'basePages', 'languages', 'baseModel'));
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
        $request = Yii::$app->request;
        $ok = true;
        $baseModel = null;

        if ($request->isPost) {
            // Mise à jour de la WebPage et de la BasePage associée
            /** @var BasePage $baseModel */
            if (!($baseModel = BasePage::findOne($request->post()['WebPage']['base_id']))) {
                $baseModel = new BasePage();
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

        // Affichage ou ré-affichage
        $basePages = BasePage::find()->orderBy('code ASC')->all();
        $languages = Language::find()->orderBy('name')->all();
        return $this->render('update', compact('model', 'basePages', 'languages', 'baseModel'));
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
        return $this->deleteModelAndredirect($this->findModel($id));
    }

}

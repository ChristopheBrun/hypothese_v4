<?php

namespace app\modules\cms\controllers;

use app\modules\cms\models\Language;
use app\modules\cms\models\BaseText;
use app\modules\cms\models\WebPage;
use app\modules\hlib\controllers\BaseController;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

use app\modules\cms\models\WebText;
use yii\data\ActiveDataProvider;

use app\modules\hlib\HLib;

/**
 * WebTextsController implements the CRUD actions for WebText model.
 */
class WebTextsController extends BaseController
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
     * @return WebText
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = WebText::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Modèle introuvable : #' . $id);
    }

    /**
     * Affichage de la liste des objets
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => WebText::find()->joinWith(['base', 'language'], true)->orderBy('base_texts.code'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'emptyBaseTexts' => BaseText::find()->withoutWebTexts()->all(),
//            'emptyBaseTexts' => BaseText::find()->withoutWebTexts()->asArray()->all(),
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
        $relatedPages = WebPage::find()->byBaseTextId($model->base_id)->language($model->language->iso_639_code)->all();
        return $this->render('view', compact('model', 'relatedPages'));
    }

    /**
     * Affichage du formulaire de création
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WebText();
        $request = Yii::$app->request;
        $ok = true;
        $baseModel = null;

        if ($request->isPost) {
            // Mise à jour du WebText et du BaseText associé
            /** @var BaseText $baseModel */
            $baseModel = BaseText::findOne($request->post()['WebText']['base_id']);

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
            $baseModel = new BaseText();
        }

        // Affichage ou ré-affichage
        $baseTexts = BaseText::find()->orderBy('code ASC')->all();
        $languages = Language::find()->orderBy('name')->all();
        return $this->render('create', compact('model', 'baseTexts', 'languages', 'baseModel'));
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
            // Mise à jour du WebText et du BaseText associé
            /** @var BaseText $baseModel */
            if (!($baseModel = BaseText::findOne($request->post()['WebText']['base_id']))) {
                $baseModel = new BaseText();
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
        $baseTexts = BaseText::find()->orderBy('code ASC')->all();
        $languages = Language::find()->orderBy('name')->all();
        return $this->render('update', compact('model', 'baseTexts', 'languages', 'baseModel'));
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
        return $this->deleteModelAndRedirect($this->findModel($id));
    }

}

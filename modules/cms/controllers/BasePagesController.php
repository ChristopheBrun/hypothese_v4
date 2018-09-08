<?php

namespace app\modules\cms\controllers;

use app\modules\cms\models\BaseText;
use app\modules\cms\queries\BasePagesQuery;
use app\modules\cms\widgets\BasePageForm;
use app\modules\hlib\controllers\BaseController;
use app\modules\hlib\helpers\h;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\modules\cms\models\BasePage;
use app\modules\hlib\HLib;

/**
 * BasePagesController implements the CRUD actions for BasePage model.
 */
class BasePagesController extends BaseController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            // backend réservé à l'administrateur
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
     * @return BasePage
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = BasePage::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Modèle introuvable : #' . $id);
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
        $relatedTexts = $model->relatedTextsByLanguage();
        return $this->render('view', compact('model', 'relatedTexts'));
    }

    /**
     * Affichage et traitement du formulaire de création
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BasePage();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if (!($this->processBaseTexts($post) && $model->load($post) && $model->save())) {
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                return $this->redirect(Url::to(['/cms/web-pages/index']));
            }
        }

        // Affichage initial ou ré-affichage en cas d'erreur
        return $this->render('create', compact('model'));
    }

    /**
     * Affichage et traitement  du formulaire de modification
     *
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if (!($this->processBaseTexts($post) && $model->load($post) && $model->save())) {
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                return $this->redirect(Url::to(['/cms/web-pages/index']));
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
        return BasePageForm::widget(['model' => $model, 'asNestedForm' => true]);
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
        return $this->deleteModelAndRedirect($this->findModel($id), Url::to(['/cms/web-pages/index']));
    }

    /**
     * Contrôle la liste des textes déclarés dans $request[nom_formulaire][relation] et crée s'il le faut de nouveaux objets dans la base.
     * Renvoie le tableau après avoir remplacé les libellés des nouveaux objets par leur identifiant.
     *
     * @param array $request
     * @return array|bool
     */
    private function processBaseTexts(array &$request)
    {
        $currentRelatedModels = ArrayHelper::getColumn(BaseText::find()->all(), 'id');
        $updatedRelatedModels = ArrayHelper::getValue($request, 'BasePage.baseTexts', []);
        if ($updatedRelatedModels === "") {
            $updatedRelatedModels = [];
        }

        foreach ($updatedRelatedModels as $i => $value) {
            if (!in_array($value, $currentRelatedModels)) {
                // $value n'est pas l'identifiant d'un texte déjà connu en base mais le libellé d'un nouvel objet
                $newRelatedModel = new BaseText(['code' => $value]);
                if (!$newRelatedModel->save()) {
                    return false;
                }

                $updatedRelatedModels[$i] = $newRelatedModel->id;
            }
        }

        $request['BasePage']['baseTexts'] = $updatedRelatedModels;
        return true;
    }

    /**
     * Met à jour la liste des index de menus
     *
     * @return \yii\web\Response
     * @throws \Throwable
     */
    public function actionResetMenuIndexes()
    {
        $this->resetMenuIndexes();
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Augmente l'index de menu d'une page. Elle permute donc avec celle qui la suivait si les index de menu se suivent.
     *
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionIncreaseMenuIndex($id)
    {
        $model = $this->findModel($id);
        $modelToSwap = BasePage::find()->byMenuIndex($model->menu_index + 1)->one();

        ++$model->menu_index;
        $model->save();
        if ($modelToSwap) {
            --$modelToSwap->menu_index;
            $modelToSwap->save();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Descend l'index de menu d'une page. Elle permute donc avec celle qui la précédait si les index de menu se suivent.
     *
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDecreaseMenuIndex($id)
    {
        $model = $this->findModel($id);
        $modelToSwap = BasePage::find()->byMenuIndex($model->menu_index - 1)->one();

        --$model->menu_index;
        $model->save();
        if ($modelToSwap) {
            ++$modelToSwap->menu_index;
            $modelToSwap->save();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Met à jour la liste des index de menus. Ceux-ci sont dédoublonnés et réalignés à partir de l'index 1. l'ordre initial des pages est
     * conservé. Si deux pages avaient le même index de menu, elles sont reclassées par #id
     * @throws \Throwable
     */
    private function resetMenuIndexes()
    {
        try {
            Yii::$app->db->transaction(function () {
                $idx = 0;
                /** @var array $row ['id' => id, 'menu_index' => val] */
                foreach (BasePagesQuery::getMenuIndexesMap() as $row) {
                    if (BasePagesQuery::updateMenuIndexes($row['id'], ++$idx) != 1) {
                        throw new Exception("Erreur sur BasePagesQuery::updateMenuIndexes({$row['id']}, $idx)");
                    }
                }
            });
        } catch (\Exception $x) {
            Yii::$app->session->addFlash('warning', HLib::t('messages', 'An error has occurred'));
            Yii::error(h::_($x), __METHOD__);
        }
    }

}

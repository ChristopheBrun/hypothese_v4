<?php

namespace app\controllers;

use app\models\TraitementTexte;
use app\modules\hlib\lib\Flash;
use app\modules\hlib\lib\WarningException;
use Exception;
use Yii;
use yii\web\Controller;

/**
 * Class LettresController
 * @package app\controllers
 */
class LettresController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $model = new TraitementTexte();

        try {
            if (Yii::$app->request->isPost) {
                if (!$model->load(Yii::$app->request->post())) {
                    throw new Exception('!$model->load()');
                }

                if (!$model->validate()) {
                    throw new WarningException('!$model->validate()');
                }

                $model->tagText();
            }
        } catch (WarningException $x) {
            Flash::warning("Il y a des erreurs de validation");
            Yii::debug($model->getErrorSummary(true));
        } catch (Exception $x) {
            Flash::error("Une erreur est survenue");
            Yii::error($x);
        }

        return $this->render('index', compact('model'));
    }

}

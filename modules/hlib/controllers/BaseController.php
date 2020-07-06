<?php

namespace app\modules\hlib\controllers;

use app\modules\hlib\HLib;
use Exception;
use Yii;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\helpers\Url;
use yii\web\Controller;


/**
 * Class BaseController
 * @package app\modules\hlib\controllers
 */
class BaseController extends Controller
{
    /**
     * Redirige le navigateur après une action de création réussie.
     * Cette méthode renvoie un objet de type Response. Elle doit être appelée sur un return comme la méthode redirect() de la classe
     * Controller. Ex. : return $this->redirectAfterCreateSuccess($model)
     *
     * @param ActiveRecord $model
     * @param string $prefixRouteWith
     * @return \yii\web\Response
     */
    protected function redirectAfterCreateSuccess(ActiveRecord $model, $prefixRouteWith = '/')
    {
        $controllerRoute = $this->getControllerRoute($prefixRouteWith);
        Yii::$app->session->setFlash('flash-success', HLib::t('messages', 'Create success'));
        /** @noinspection PhpUndefinedFieldInspection */
        $requestedRedirection =
            Yii::$app->request->getBodyParam('action') == 'saveAndKeepEditing' ?
                Url::toRoute([$controllerRoute . '/update', 'id' => $model->id]) :
                Url::toRoute($controllerRoute . '/index');
        return $this->redirect($requestedRedirection);
    }

    /**
     * Renvoie le fragment de route qui donne accès au contrôleur
     *
     * @param string $prefixRouteWith Par défaut, la route renvoyée sera préfixée par '/', renseigner cet argument dans les autres cas
     * @return string
     */
    protected function getControllerRoute($prefixRouteWith = '/')
    {
        $route = explode('/', $this->route);
        array_pop($route);
        return $prefixRouteWith . implode('/', $route);
    }

    /**
     * Suppression d'un objet
     * Cette méthode renvoie un objet de type Response. Elle doit être appelée sur un return comme la méthode redirect() de la classe
     * Controller. Ex. : return $this->redirectAfterCreateSuccess($model)
     *
     * @param ActiveRecord $model
     * @param string|null $redirectTo
     * @return \yii\web\Response
     * @throws \Throwable
     */
    protected function deleteModelAndRedirect(ActiveRecord $model, $redirectTo = null)
    {
        try {
            if (!$model->delete()) {
                Yii::$app->session->setFlash('flash-danger', HLib::t('messages', 'Delete unsuccessful'));
            } else {
                Yii::$app->session->setFlash('flash-success', HLib::t('messages', 'Delete success'));
            }
        } catch (StaleObjectException $s) {
            Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'This object is outdated. Deletion failed'));
        } catch (Exception $s) {
            Yii::$app->session->setFlash('flash-danger', HLib::t('messages', 'An error occured during the process. Deletion failed'));
        }

        if (is_null($redirectTo)) {
            $redirectTo = Url::to([$this->getControllerRoute() . '/index']);
        }

        return $this->redirect($redirectTo);
    }

}
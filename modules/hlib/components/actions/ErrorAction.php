<?php

namespace app\modules\hlib\components\actions;

use app\modules\hlib\HLib;
use Yii;
use yii\base\Exception;
use yii\base\UserException;
use yii\web\HttpException;

/**
 *
 */
class ErrorAction extends \yii\web\ErrorAction
{
    /**
     * Runs the action
     *
     * @return string result content
     */
    public function run(): string
    {
        // Reprise du codede la méthode mère
        if ($this->layout !== null) {
            $this->controller->layout = $this->layout;
        }

        Yii::$app->getResponse()->setStatusCodeByException($this->exception);

        if (Yii::$app->getRequest()->getIsAjax()) {
            return $this->renderAjaxResponse();
        }

        // Nos traitements
        if ($this->exception instanceof HttpException) {
            $code = $this->exception->statusCode;
        } else {
            $code = $this->exception->getCode();
        }

        if ($this->exception instanceof Exception) {
            $name = $this->exception->getName();
        } else {
            $name = $this->defaultName ?: Yii::t('yii', 'Error');
        }

        if ($code) {
            $name .= " (#$code)";
        }

        if ($this->exception instanceof UserException) {
            $message = $this->exception->getMessage();
        } else {
            $message = $this->defaultMessage ?: HLib::t('messages', 'An error occurred during the process');
        }

        // Le modèle de page utilisé dépend du code de l'erreur. Par défaut, c'est 'error.php'
        switch ($code) {
            case '404' :
                $this->view = 'error404';
                break;
            default:
                break;
        }

        // On remplace l'appel à renderHtmlResponse() pour passer les arguments à la vue
        return $this->controller->render($this->view ?: $this->id, compact('name', 'message', 'exception'));
    }
}

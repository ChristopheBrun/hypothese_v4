<?php

namespace app\modules\hlib\components\actions;

use app\modules\hlib\HLib;
use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\base\UserException;
use yii\web\HttpException;

/**
 *
 */
class ErrorAction extends Action
{
    /**
     * @var string the view file to be rendered. If not set, it will take the value of [[id]].
     * That means, if you name the action as "error" in "SiteController", then the view name
     * would be "error", and the corresponding view file would be "views/site/error.php".
     */
    public $view;
    /**
     * @var string the name of the error when the exception name cannot be determined.
     * Defaults to "Error".
     */
    public $defaultName;
    /**
     * @var string the message to be displayed when the exception message contains sensitive information.
     * Defaults to "An internal server error occurred.".
     */
    public $defaultMessage;


    /**
     * Runs the action
     *
     * @return string result content
     */
    public function run()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            // action has been invoked not from error handler, but by direct route, so we display '404 Not Found'
            $exception = new HttpException(404, Yii::t('yii', 'Page not found.'));
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        }
        else {
            $code = $exception->getCode();
        }

        if ($exception instanceof Exception) {
            $name = $exception->getName();
        }
        else {
            $name = $this->defaultName ?: Yii::t('yii', 'Error');
        }

        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        }
        else {
            $message = $this->defaultMessage ?: HLib::t('messages', 'An error occurred during the process');
        }

        if (Yii::$app->getRequest()->getIsAjax()) {
            // Requête AJAX : on renvoie juste de quoi identifier l'erreur
            return "$name: $message";
        }

        // Le modèle de page utilisé dépend du code de l'erreur. Par défaut, c'est 'error.php'
        switch ($code) {
            case '404' :
                $this->view = 'error404';
                break;
            default:
                break;
        }

        return $this->controller->render($this->view ?: $this->id, compact('name', 'message', 'exception'));
    }
}

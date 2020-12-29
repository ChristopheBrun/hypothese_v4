<?php


namespace app\filters;


use Exception;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\web\Request;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use yii\web\User;

/**
 * Class HttpBasicAuth
 * @package app\components
 */
class HttpBasicAuth extends \yii\filters\auth\HttpBasicAuth
{
    /**
     * Surcharge pour passer BasicAuth sur les serveurs PHPNet
     * @todo_cbn A passer dans hlib si son utilité se confirme
     *
     * @param User $user
     * @param Request $request
     * @param Response $response
     * @return bool|mixed|IdentityInterface|null
     * @throws UnauthorizedHttpException
     * @throws Exception
     */
    public function authenticate($user, $request, $response)
    {
        // @internal
        // Le composant HttpBasicAuth d'origine cherche PHP_AUTH_USER dans les headers de la requête en faisant
        // un appel à $request->getAuthCredentials() qui, pour une raison que j'ignore, ne trouve ni
        // HTTP_AUTHORIZATION ni REDIRECT_HTTP_AUTHORIZATION et qui est donc incapable d'initialiser $auth_token
        // dans le cas où Apache fonctionne avec un CGI.
        // Avant de passer par cette méthode, il faut donc forcer nous mêmes l'initialisation de PHP_AUTH_USER
        if (ArrayHelper::getValue($_SERVER, 'PHP_AUTH_USER') === null) {
            $authToken =
                ArrayHelper::getValue($_SERVER, 'HTTP_AUTHORIZATION') ?:
                    ArrayHelper::getValue($_SERVER, 'REDIRECT_HTTP_AUTHORIZATION');
            if ($authToken !== null && strncasecmp($authToken, 'basic', 5) === 0) {
                $parts = array_map(function ($value) {
                    return strlen($value) === 0 ? null : $value;
                }, explode(':', base64_decode(mb_substr($authToken, 6)), 2));

                $_SERVER['PHP_AUTH_USER'] = $parts[0];
            }
        }
        Yii::debug($_SERVER);

        return parent::authenticate($user, $request, $response);
    }
}
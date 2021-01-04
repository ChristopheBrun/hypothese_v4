<?php /** @noinspection PhpUnused */

namespace app\controllers;

use app\lib\enums\PregmatchType;
use app\lib\helpers\UtilitairesHelper;
use app\modules\hlib\lib\Flash;
use Exception;
use Yii;
use yii\web\Controller;

/**
 * Class UtilitairesController
 * @package app\controllers
 */
class UtilitairesController extends Controller
{
    /**
     * Traduit un path windows en path compatible linux
     *
     * @return string
     * @internal fait en javascript directement sur la page
     *
     */
    public function actionPathWindows(): string
    {
        return $this->render('path-windows');
    }

    /**
     * Affiche les résultats de calcul d'une regex PHP
     *
     * @return string
     * @internal fait en javascript directement sur la page
     */
    public function actionRegex(): string
    {
        $string = '';
        $regex = '';
        $pregmatch = PregmatchType::SIMPLE;
        $result = null;
        $matches = [];
        try {
            if (Yii::$app->request->isPost) {
                $string = Yii::$app->request->post('string');
                $regex = trim(Yii::$app->request->post('regex'));
                $pregmatch = Yii::$app->request->post('pregmatch');
                $parentheses = UtilitairesHelper::parseParenthesesRegex($regex);

                $result =
                    $pregmatch == PregmatchType::SIMPLE ?
                        preg_match($regex, $string, $matches) : preg_match_all($regex, $string, $matches);

                if ($result === false) {
                    Flash::error("Une erreur est survenue dans le traitement de l'expression régulière");
                } else {
                    Flash::success("Chaine analysée");
                }
            }
        } catch (Exception $x) {
            Yii::error($x->getMessage());
            Flash::error($x->getMessage());
        }

        return $this->render('regex', compact('string', 'regex', 'pregmatch', 'result', 'matches', 'parentheses'));
    }

}
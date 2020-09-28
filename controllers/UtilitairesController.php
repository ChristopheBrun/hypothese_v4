<?php /** @noinspection PhpUnused */

/**
 * Created by PhpStorm.
 * User: Christophe
 * Date: 30/06/2020
 * Time: 14:47
 */

namespace app\controllers;

use app\models\RegexForm;
use app\modules\hlib\lib\Flash;
use Exception;
use Yii;
use yii\web\Controller;

/**
 * Class HelperController
 * @package app\controllers
 */
class UtilitairesController extends Controller
{

    /**
     * Traduit un path windows en path compatible linux
     * @return string
     * @internal fait en javacsript directement sur la page
     *
     */
    public function actionPathWindows()
    {
        return $this->render('path-windows');
    }

    /**
     * Traduit un path windows en path compatible linux
     * @return string
     * @internal fait en javacsript directement sur la page
     *
     */
    public function actionRegex()
    {
        $string = '';
        $regex = '';
        $result = null;
        $matches = [];
        try {
            if (Yii::$app->request->isPost) {
                $string = Yii::$app->request->post('string');
                $regex = Yii::$app->request->post('regex');

                $result = preg_match($regex, $string, $matches);
                Flash::success("Chaine analysée");
            }
        } catch (Exception $x) {
            Yii::error($x->getMessage());
            Flash::error($x->getMessage());
        }

        return $this->render('regex', compact('string', 'regex', 'result', 'matches'));
    }
}
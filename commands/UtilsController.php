<?php
/**
 * Created by PhpStorm.
 * User: Christophe
 * Date: 27/11/2019
 * Time: 16:06
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\FileHelper;

/**
 * Class UtilsController
 * @package app\commands
 *
 * Commandes diverses (scripts ponctuels, etc)
 */
class UtilsController extends Controller
{
    /**
     *
     */
    public function actionUpdateNomsFichiers()
    {
        $dirname = 'F:\etc\test';
        $files = FileHelper::findFiles($dirname);

        foreach ($files as $filename) {
            $newName = str_replace('gragmentdunomdefichiertroplong', '', $filename);
            rename($filename, $newName);
            $this->stdout(sprintf("Ancien : %s >> \nNouveau : %s\n---------\n", basename($filename), basename($newName)));
        }

        return ExitCode::getReason(ExitCode::OK);
    }

}
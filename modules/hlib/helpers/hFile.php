<?php

namespace app\modules\hlib\helpers;

use Yii;
use yii\helpers\FileHelper;


/**
 * Class hFile
 * @package app\modules\hlib\helpers
 */
class hFile extends FileHelper
{
    /**
     * Helper pour la suppression d'un fichier physique sur le serveur
     *
     * @param string $filename Chemin d'accès complet au fichier
     * @param bool   $throws true => lance une exception en cas d'erreur. Sinon, renverra false
     * @return bool
     * @throws \Exception
     */
    public static function delete($filename, $throws = false)
    {
        if (!is_file($filename)) {
            Yii::warning("$filename n'est pas un fichier", __METHOD__);
            return false;
        }

        if (!unlink($filename)) {
            if ($throws) {
                throw new \Exception("Erreur lors de la suppression du fichier $filename");
            }
            return false;
        }

        return true;
    }

    /**
     * @param $filename
     * @return string file extension
     */
    public static function getExtension($filename)
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }
}
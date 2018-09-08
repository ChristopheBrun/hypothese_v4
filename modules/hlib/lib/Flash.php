<?php

namespace app\modules\hlib\lib;

use Yii;

/**
 * Class Flash
 */
class Flash
{
    /** @var array */
    private static $pendingFlashes = [];

    /**
     * @param string $message
     * @param bool $removeOnlyAfterAccess
     */
    public static function error($message, $removeOnlyAfterAccess = true)
    {
        Yii::$app->session->addFlash('danger', $message, $removeOnlyAfterAccess);
    }

    /**
     * @param string $message
     * @param bool $removeOnlyAfterAccess
     */
    public static function alert($message, $removeOnlyAfterAccess = true)
    {
        Yii::$app->session->addFlash('danger', $message, $removeOnlyAfterAccess);
    }

    /**
     * @param string $message
     * @param bool $removeOnlyAfterAccess
     */
    public static function danger($message, $removeOnlyAfterAccess = true)
    {
        Yii::$app->session->addFlash('danger', $message, $removeOnlyAfterAccess);
    }

    /**
     * @param string $message
     * @param bool $removeOnlyAfterAccess
     */
    public static function warning($message, $removeOnlyAfterAccess = true)
    {
        Yii::$app->session->addFlash('warning', $message, $removeOnlyAfterAccess);
    }

    /**
     * @param string $message
     * @param bool $removeOnlyAfterAccess
     */
    public static function success($message, $removeOnlyAfterAccess = true)
    {
        Yii::$app->session->addFlash('success', $message, $removeOnlyAfterAccess);
    }

    /**
     * @param string $message
     * @param bool $removeOnlyAfterAccess
     */
    public static function info($message, $removeOnlyAfterAccess = true)
    {
        Yii::$app->session->addFlash('info', $message, $removeOnlyAfterAccess);
    }

    /**
     * Sauvegarde les flash en attente (utile lors du logout, quand la session risque d'être détruite)
     */
    public static function savePendingFlashes()
    {
        static::$pendingFlashes = Yii::$app->session->allFlashes;
    }

    /**
     * Permet de relancer pour une requête les flash qui sont en attente (utile quand une redirection menace de les effacer)
     *
     * @param bool $removeOnlyAfterAccess
     */
    public static function reloadPendingFlashes($removeOnlyAfterAccess = true)
    {
        foreach (static::$pendingFlashes as $key => $flashes) {
            foreach ($flashes as $message) {
                Yii::$app->session->addFlash($key, $message, $removeOnlyAfterAccess);
            }
        }
    }
}
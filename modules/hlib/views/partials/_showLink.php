<?php
/**
 * Accès au lien vers la page de visualisation du modèle
 *
 * @var $url string
 * @deprecated
 */
use app\modules\hlib\HLib;

?>
<a href="<?= $url ?>" class="btn btn-info btn-xs" title="<?= HLib::t('labels', 'Show') ?>">
    <span class="glyphicon glyphicon-eye-open"></span>
</a>


<?php
/**
 * Accès au lien vers la page de mise à jour du modèle
 *
 * @var $url string
 * @deprecated
 */
use app\modules\hlib\HLib;

?>
<a href="<?= $url ?>" class="btn btn-success btn-xs" title="<?= HLib::t('labels', 'Edit') ?>">
    <span class="glyphicon glyphicon-pencil"></span>
</a>


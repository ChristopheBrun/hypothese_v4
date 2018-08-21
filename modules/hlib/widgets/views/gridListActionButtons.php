<?php
/**
 * Affiche les boutons de la colonne 'action' de la gridListView (backend, action = index)
 */
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\DeleteLink;

/**
 * @var string $updateUrl
 * @var string $viewUrl
 * @var string $deleteUrl
 * @var string $deleteMessageData
 */

?>

<a href="<?= $updateUrl ?>" class="btn btn-success btn-xs" title="<?= HLib::t('labels', 'Edit') ?>">
    <span class="glyphicon glyphicon-pencil"></span>
</a>

<a href="<?= $viewUrl ?>" class="btn btn-info btn-xs" title="<?= HLib::t('labels', 'View') ?>">
    <span class="glyphicon glyphicon-eye-open"></span>
</a>

<?= DeleteLink::widget(['url' => $deleteUrl, 'data' => $deleteMessageData]) ?>


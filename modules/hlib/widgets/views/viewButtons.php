<?php
/**
 * Barre de boutons affichée dans la fiche d'un objet. Elle permet les actions suivantes : retour à la liste (index), accès au
 * formulaire de modification (update) et suppression de l'objet (delete)
 *
 * @var string $indexUrl
 * @var string $updateUrl
 * @var string $deleteUrl
 * @var array  $additionalButtons
 */
use app\modules\hlib\HLib;
use yii\helpers\Html;

if(!isset($additionalButtons)) {
    $additionalButtons = [];
}

?>
<div class="view-buttons">
    <?= Html::a(HLib::t('labels', 'Back to List'), $indexUrl, ['class' => 'btn btn-primary']) ?>

    <?= Html::a(HLib::t('labels', 'Update'), $updateUrl, ['class' => 'btn btn-success']) ?>

    <?= Html::a(HLib::t('labels', 'Delete'), $deleteUrl, [
        'class' => 'btn btn-danger',
        'data' => ['confirm' => HLib::t('messages', 'Are you sure you want to delete this item?'), 'method' => 'delete',],
    ]) ?>

    <?php
    foreach ($additionalButtons as $btn) :
        echo Html::a(HLib::t('labels', $btn['label']), $btn['url'], ['class' => 'btn ' . $btn['class']]);
    endforeach;
    ?>
</div>

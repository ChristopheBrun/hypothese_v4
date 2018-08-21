<?php
/**
 * @var string $indexUrl
 * @var string $deleteUrl Si elle est renseignée, le bouton 'supprimer' est aussi affiché
 * @deprecated
 */

use app\modules\hlib\HLib;
use yii\helpers\Html;

?>
<div class="form-group">
    <?= Html::submitButton(HLib::t('labels', 'Save & keep editing'), [
        'class' => 'btn btn-success',
        'name' => 'action',
        'value' => 'saveAndKeepEditing']) ?>

    <?= Html::submitButton(HLib::t('labels', 'Save & back to list'), [
        'class' => 'btn btn-primary',
        'name' => 'action',
        'value' => 'saveAndBackToList']) ?>

    <?= Html::a(HLib::t('labels', 'Cancel & back to list'), $indexUrl, [
        'class' => 'btn btn-warning',
        'name' => 'action',
        'value' => 'cancel']) ?>

    <?php
    // todo_cbn Il y a un bug dans yii qui fait que la méthode delete du lien est ignorée. Remettre la lien
    // une fois que ce sera corrigé (nb : toujours pas fait en 2.0.6)
    if(0) :
    ?>
    <?php if (isset($deleteUrl)) {
        echo Html::a(HLib::t('labels', 'Delete & back to list'), $deleteUrl, [
            'class' => 'btn btn-danger',
            'data' => ['confirm' => HLib::t('messages', 'Are you sure you want to delete this item?'), 'method' => 'delete',]
        ]);
    } ?>
    <?php endif; ?>
</div>

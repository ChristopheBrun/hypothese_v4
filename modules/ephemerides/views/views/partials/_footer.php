<?php
/**
 *
 */
use app\modules\hlib\HLib;

?>

<div class="row">
    <div class="footer">
        Ephémérides.eu &copy; 2015 - <?= date('Y'); ?> --- <?= HLib::t('labels', 'Version') . ' ' . Yii::$app->version ?>
    </div>
</div>
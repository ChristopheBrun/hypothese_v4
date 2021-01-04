<?php

use Carbon\Carbon;

/**
 * Footer interne avec date/heure d'affichage de la page
 * @var $dateMaj string|null
 */

?>
<div class="panel-footer">
    <div class="row">
        <div class="col-sm-3">
            <?= $dateMaj ? "Mise à jour le : $dateMaj" : '' ?>
        </div>
        <div class="col-sm-9 text-right">
            <?= Carbon::now()->format('d/m/Y H:i:s') ?>
        </div>
    </div>
</div>
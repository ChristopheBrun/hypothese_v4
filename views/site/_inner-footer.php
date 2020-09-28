<?php

use Carbon\Carbon;

/**
 * Footer interne avec date/heure d'affichage de la page
 */

$dateStr = Carbon::now()->format('d/m/Y H:i:s');
?>
<div class="panel-footer">
    <div class="row">
        <div class="col-sm-12">
            <?= $dateStr ?>
        </div>
    </div>
</div>
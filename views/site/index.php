<?php

use Carbon\Carbon;

/**
 * Page d'accueil du site
 */

/** @var  yii\web\View $this */

$this->title = Yii::$app->name;
$dateStr = Carbon::now()->isoFormat('%A %d %B %Y');

?>

<div class="panel panel-default" id="homepage">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12">
                <h1>Accueil du site</h1>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <p>
                    Bienvenue sur mon site personnel, sur lequel vous ne trouverez pas grand chose pour le moment.
                    Les travaux de remise à jour sont en cours, et ils devraient trainer un peu
                    en longueur.
                </p>
                <ul>
                    <li>Ephémérides (en cours de restauration)</li>
                    <li>Quelques utilitaires disparates à usage essentiellement personnel :
                        <ol>
                            <li>les couleurs (en cours)</li>
                            <li>les images (en cours)</li>
                        </ol>
                    </li>
                    <li>... et si vous souhaitez consulter mon CV, ce sera bientôt
                        par <?= \yii\helpers\Html::a('ici', ['']) ?></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-12">
                Merci de votre visite.
            </div>
        </div>
    </div>
</div>

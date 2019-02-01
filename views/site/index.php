<?php

use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\form\CalendarEntrySearchForm;
use Carbon\Carbon;

/**
 * Page d'accueil du site
 *
 * @var yii\web\View $this
 * @var CalendarEntry $lastUpdatedEntry
 * @var int $countDaysWithEntries
 * @var int $countEntries
 * @var array $dailyEntries [CalendarEntry]
 * @var CalendarEntry $previousEntry CalendarEntry null, renseigné seulement si $dailyEntries est vide
 * @var CalendarEntry $nextEntry CalendarEntry null, renseigné seulement si $dailyEntries est vide
 * @var CalendarEntrySearchForm $searchModel
 * @var array $tags [Tag]
 */

$this->title = Yii::$app->name;
$dateStr = Carbon::now()->isoFormat('%A %d %B %Y');

?>
<div class="row">
    <div class="panel panel-default" id="homepage">
        <div class="panel-heading">
            <h1>Bonjour</h1>
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
                    et voilà !
                </div>
            </div>
        </div>
    </div>
</div>

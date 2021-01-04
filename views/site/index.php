<?php

use Carbon\Carbon;
use yii\helpers\Html;

/**
 * Page d'accueil du site
 */

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
                    Bienvenue sur mon site personnel, sur lequel vous ne trouverez pas grand chose en zone publique
                    pour le moment. Les travaux de mise à jour sont en cours et ils devraient encore trainer un peu.
                </p>
            </div>
        </div>
    </div>

    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-12">
                Merci de votre visite. N'hésitez pas à me soutenir en cotisant à
                mon <?= Html::a("teepee", ['/site/joke']) ?>.
                <br/>
                <span style="font-style: italic;font-size: smaller">(non, je plaisante...)</span>
            </div>
        </div>
    </div>
</div>

<?php

use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\form\CalendarEntrySearchForm;
use app\modules\ephemerides\models\Tag;
use app\modules\ephemerides\widgets\DisplayCalendarEntries;
use Carbon\Carbon;
use yii\helpers\Html;

/**
 * Page d'accueil du site
 *
 * @var CalendarEntrySearchForm $searchModel
 * @var CalendarEntry[] $dailyEntries
 * @var Tag[] $tags
 */

$this->title = Yii::$app->name;
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
Yii::$app->addKeywordsMetaTags(['PHP']);

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
        <?= /** @noinspection PhpUnhandledExceptionInspection */
        DisplayCalendarEntries::widget([
            'models' => $dailyEntries,
            'tags' => $tags,
            'showTagsAsButtons' => false,
            'tagsButtonsRoute' => ['/site/pas-cliquer'],
        ]) ?>
    </div>

    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-12">
                Merci de votre visite. N'hésitez pas à me soutenir en cotisant à
                mon <?= Html::a("teepee", ['/site/joke']) ?>.
            </div>
        </div>
    </div>
</div>

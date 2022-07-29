<?php

use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\form\CalendarEntrySearchForm;
use app\modules\ephemerides\models\Tag;
use app\modules\ephemerides\widgets\DisplayCalendarEntries;
use app\modules\user\lib\enums\AppRole;
use Carbon\Carbon;
use yii\helpers\Html;
use yii\helpers\Url;

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
        <?php if (!$dailyEntries) : ?>
            <div class="row">
                <div class="col-sm-12">
                    <p>
                        Il n'y rien à voir aujourd'hui...
                        <?= Html::img(
                            Url::base(true) . '/images/etienne-a-cheval.png',
                            ['alt' => 'rien à voir!', 'style' => 'float:right']
                        ) ?>
                    </p>
                </div>
            </div>
        <?php else: ?>
            <?=
            /** @noinspection PhpUnhandledExceptionInspection */
            DisplayCalendarEntries::widget([
                'models' => $dailyEntries,
                'tagsButtonsAltRoute' => ['/site/pas-cliquer'],
                'showAdminButton' => Yii::$app->user->can(AppRole::SUPERADMIN),
                'showDirectLink' => true,
            ]) ?>
        <?php endif ?>
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

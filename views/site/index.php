<?php
use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\form\CalendarEntrySearchForm;
use Carbon\Carbon;

/**
 * Page d'accueil du site
 *
 * @var yii\web\View            $this
 * @var CalendarEntry           $lastUpdatedEntry
 * @var int                     $countDaysWithEntries
 * @var int                     $countEntries
 * @var array                   $dailyEntries [CalendarEntry]
 * @var CalendarEntry           $previousEntry CalendarEntry null, renseigné seulement si $dailyEntries est vide
 * @var CalendarEntry           $nextEntry CalendarEntry null, renseigné seulement si $dailyEntries est vide
 * @var CalendarEntrySearchForm $searchModel
 * @var array                   $tags [Tag]
 */

$this->title = Yii::$app->name;
$dateStr = Carbon::now()->isoFormat('%A %d %B %Y');

?>
<div class="row">
    <div class="panel panel-default" id="homepage">
        <div class="panel-heading">
            <h1><?= Yii::t('labels', 'Ephemerides of France and Europe') ?></h1>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <?= $this->render('@app/modules/ephemerides/views/calendar-entry/_frontendSearchForm', ['model' => $searchModel, 'tags' => $tags]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12" id="today-block">
                    <div class="current-date"><?= ucfirst(utf8_encode($dateStr)); ?></div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <?php if (count($dailyEntries)): ?>

                            <?php foreach ($dailyEntries as $entry): ?>
                                <div class="col-sm-12">
                                    <?= $this->render('@app/modules/ephemerides/views/calendar-entry/_entry', ['entry' => $entry]) ?>
                                </div>
                            <?php endforeach ?>

                        <?php else: ?>

                            <div class="col-sm-12">
                                <?= Yii::t('messages', 'No calendar entries today') ?>...
                            </div>
                            <div class="col-sm-12">
                                ... <?= Yii::t('messages', 'but last days we had') ?>...
                                <?= $this->render('@app/modules/ephemerides/views/calendar-entry/_entry', ['entry' => $previousEntry]) ?>
                            </div>
                            <div class="col-sm-12">
                                ... <?= Yii::t('messages', 'and next days we will have') ?>...
                                <?= $this->render('@app/modules/ephemerides/views/calendar-entry/_entry', ['entry' => $nextEntry]) ?>
                            </div>

                        <?php endif ?>
                    </div>
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

<?php
/**
 * Fiche complète d'une éphéméride (frontend)
 */
use app\models\Article;
use app\models\CalendarEntry;
use app\modules\hlib\HLib;
use Carbon\Carbon;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $model app\models\CalendarEntry
 * @var $previousEntryInChronology app\models\CalendarEntry
 * @var $nextEntryInChronology app\models\CalendarEntry
 * @var $previousEntryInCalendar app\models\CalendarEntry
 * @var $nextEntryInCalendar app\models\CalendarEntry
 */

$this->title = $model->title;
$fullTitle = Carbon::parse($model->event_date)->format(CalendarEntry::DATE_FORMAT_DAY) . ' : ' . $model->title;

/** @var \app\modules\users\WebUser $webUser */
$webUser = Yii::$app->user;

/** @var array $articles [Articles] */
$articles = $model->enabledArticles();

?>
<div class="row panel panel-default" id="show-calendar-entry">
    <div class="panel-heading">

        <div class="row">
            <div class="col-sm-6">
                <?= Html::img($model->getImageUrl('lg', true), ['alt' => $model->getSlug(), 'class' => 'img-responsive img-thumbnail']) ?>
            </div>
            <div class="col-sm-6">
                <h1><?= Html::encode($fullTitle) ?></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 image-caption">
                <span>Illustration : </span><?= $model->image_caption ?>
            </div>
        </div>

    </div>

    <div class="panel-body">

        <div class="row calendar-entry-text">
            <div class="col-sm-12">
                <?= nl2br($model->body) ?>
            </div>
        </div>

        <?= $this->render('/site/_tagsButtons', ['model' => $model]) ?>

    </div>

    <div class="panel-footer">

        <div>
            <?php if (count($articles)) :
                foreach ($articles as $article) :
                    /** @var Article $article */
                    ?>
                    <div>
                        <?= Html::a('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp' . $article->title,
                            Url::to(['/articles/show', 'id' => $article->id, 'slug' => $article->getSlug()])
                        ) ?>
                    </div>
                <?php endforeach;
            endif ?>
        </div>

        <hr/>

        <?php if($webUser->isAdmin() || $webUser->isSuperAdmin()) : ?>
                <?= Html::a(HLib::t('labels', 'Update item'), Url::to(['calendar-entries/update', 'id' => $model->id]), ['class' => 'btn btn-success']) ?>
                <?= Html::a(HLib::t('labels', 'View'), Url::to(['calendar-entries/view', 'id' => $model->id]), ['class' => 'btn btn-info']) ?>
            <hr/>
        <?php endif ?>

        <div class="row">
            <div class="col-sm-4">
                <?php
                if ($previousEntryInCalendar) :
                    echo Html::a('<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>&nbsp' . Yii::t('labels', 'Previous entry (calendar)'),
                        Url::to(['/calendar-entries/show', 'id' => $previousEntryInCalendar->id, 'slug' => $previousEntryInCalendar->getSlug()]));
                else :
                    echo "&nbsp;";
                endif;
                ?>
            </div>
            <div class="col-sm-4 text-center">
                &nbsp;
            </div>
            <div class="col-sm-4 text-right">
                <?php
                if ($nextEntryInCalendar) :
                    echo Html::a('<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>&nbsp' . Yii::t('labels', 'Next entry (calendar)'),
                        Url::to(['/calendar-entries/show', 'id' => $nextEntryInCalendar->id, 'slug' => $nextEntryInCalendar->getSlug()]));
                else :
                    echo "&nbsp;";
                endif;
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <?php
                if ($previousEntryInChronology) :
                    echo Html::a('<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>&nbsp' . Yii::t('labels', 'Previous entry (chronology)'),
                        Url::to(['/calendar-entries/show', 'id' => $previousEntryInChronology->id, 'slug' => $previousEntryInChronology->getSlug()]));
                else :
                    echo "&nbsp;";
                endif;
                ?>
            </div>
            <div class="col-sm-4 text-center">
                <?= Html::a('<span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp' . HLib::t('labels', 'Home'),
                    Url::to(['/site/index'])) ?>
            </div>
            <div class="col-sm-4 text-right">
                <?php
                if ($nextEntryInChronology) :
                    echo Html::a('<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>&nbsp' . Yii::t('labels', 'Next entry (chronology)'),
                        Url::to(['/calendar-entries/show', 'id' => $nextEntryInChronology->id, 'slug' => $nextEntryInChronology->getSlug()]));
                else :
                    echo "&nbsp;";
                endif;
                ?>
            </div>
        </div>

    </div>

</div>

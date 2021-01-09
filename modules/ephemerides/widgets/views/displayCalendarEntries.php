<?php

use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\Tag;
use Carbon\Carbon;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Affichage d'une liste d'éphémérides
 *
 * @var CalendarEntry[] $models
 * @var Tag[] $tags
 * @var bool $showTagsAsButtons
 * @var string|array $tagsButtonsRoute
 */

if (!$models) {
    return;
}

?>

<?php foreach ($models as $entry) : ?>
    <div class="calendar-entry-block">
        <div class="row">
            <div class="col-sm-4">
                <div>
                    <?php if ($entry->hasImage() & file_exists($entry->getImagePath('std', true))): ?>
                        <?= Html::img($entry->getImageUrl('lg'), ['class' => 'img-responsive img-thumbnail', 'alt' => $entry->getSlug()]) ?>
                    <?php else : ?>
                        &nbsp;-&nbsp;
                    <?php endif ?>
                </div>
            </div>

            <div class="col-sm-8">
                <div class="event-date">
                    <i class="far fa-calendar-alt"></i>
                    <?php
                    $carbon = new Carbon($entry->event_date, 'Europe/Paris');
                    echo $carbon->formatLocalized('%d %B %Y');
                    ?>
                </div>

                <div>
                    <h2><?= Html::encode($entry->title) ?></h2>
                </div>

                <div class="calendar-entry-text">
                    <?= nl2br($entry->description) ?>
                </div>

                <div class="calendar-entry-tags">
                    <?php foreach ($entry->tags as $tag) : ?>
                        <?php if ($showTagsAsButtons && count($entry->tags)) : ?>
                            <?= Html::a(
                                Html::encode($tag->label),
                                Url::to(['/calendar-entries/post-search', 'tag' => $tag->id]), ['class' => 'btn btn-primary']
                            ) ?>
                        <?php else : ?>
                            <?= Html::a(
                                Html::encode($tag->label),
                                Url::to($tagsButtonsRoute), ['class' => 'btn btn-primary']
                            ) ?>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach ?>
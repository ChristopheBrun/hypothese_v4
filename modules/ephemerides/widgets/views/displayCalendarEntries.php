<?php

use app\modules\ephemerides\models\CalendarEntry;
use app\modules\hlib\helpers\DateHelper;
use app\modules\hlib\helpers\hString;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * Affichage d'une liste d'éphémérides
 *
 * @var CalendarEntry[] $models
 * @var string|array $tagsButtonsAltRoute
 * @var bool $showAdminButton
 * @var bool $showDirectLink
 */

if (!$models) {
    return;
}

if ($showDirectLink) {
    $js = <<<JS
    $('.copy-link').on('click', function () {
        console.log('.copy-link click');
        var temp = $("<input>");
        $("body").append(temp);
        temp.val($(this).prev().attr('href')).select();
        document.execCommand("copy");
        temp.remove();
        console.log($(this).prev().attr('href'));
    });
JS;
    $this->registerJs($js, View::POS_END);
}

?>

<?php foreach ($models as $entry) : ?>
    <div class="calendar-entry-block">
        <div class="row">
            <div class="col-sm-4">
                <div class="image">
                    <?php if ($entry->hasImage() & file_exists($entry->getImagePath('std', true))): ?>
                        <?= Html::img($entry->getImageUrl('lg'), ['class' => 'img-responsive img-thumbnail', 'alt' => $entry->getSlug()]) ?>
                    <?php else : ?>
                        &nbsp;-&nbsp;
                    <?php endif ?>
                </div>
                <div class="legende">
                    <?= $entry->image_caption ?>
                </div>
            </div>

            <div class="col-sm-8">
                <div class="event-date">
                    <i class="far fa-calendar-alt"></i>
                    <?php
                    try {
                        $date = new DateTimeImmutable($entry->event_date, new DateTimeZone('Europe/Paris'));
                        echo hString::forceUTF8(DateHelper::dateSQLToLocalized($entry->event_date));
                    } catch (\Throwable $x) {
                        echo "[...]";
                    }
                    ?>
                </div>

                <div>
                    <h2><?= Html::encode($entry->title) ?></h2>
                </div>

                <div class="description">
                    <?= nl2br($entry->description) ?>
                </div>

                <div class="tags">
                    <?php foreach ($entry->tags as $tag) : ?>
                        <?php if ($tagsButtonsAltRoute === null && count($entry->tags)) : ?>
                            <?= Html::a(
                                Html::encode($tag->label),
                                Url::to(['/calendar-entries/post-search', 'tag' => $tag->id]), ['class' => 'btn btn-primary']
                            ) ?>
                        <?php elseif ($tagsButtonsAltRoute !== null) : ?>
                            <?= Html::a(
                                Html::encode($tag->label),
                                Url::to($tagsButtonsAltRoute), ['class' => 'btn btn-primary']
                            ) ?>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>

                <?php if ($showAdminButton) : ?>
                    <?= Html::a(
                        "<i class='fas fa-marker'></i>&nbsp;Voir la fiche en backend",
                        Url::to(['/ephemerides/calendar-entry/view', 'id' => $entry->id], true),
                        ['class' => 'btn btn-info']) ?>
                <?php endif ?>

                <?php if ($showDirectLink) : ?>
                    <?= Html::a(
                        "<i class='fas fa-eye'></i>&nbsp;Voir la fiche",
                        Url::to(['/ephemerides/calendar-entry/show', 'id' => $entry->id], true),
                        ['class' => 'btn btn-info frontend-link', 'alt' => $entry->title]) ?>
                    <?php if ($showAdminButton) : ?>
                        <button class="btn btn-default copy-link">
                            <i class="fas fa-link"></i>&nbsp;Copier le lien
                        </button>
                    <?php endif ?>
                <?php endif ?>
            </div>
        </div>
    </div>
<?php endforeach ?>
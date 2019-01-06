<?php
/**
 * Bloc d'affichage d'une éphéméride en frontend.
 *
 * @var CalendarEntry $entry
 * @var View $this
 */

use app\modules\ephemerides\models\CalendarEntry;
use app\modules\hlib\helpers\hDate;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

?>
<div class="calendar-entry-block">
    <div class="row">

        <div class="col-sm-4">
            <div class="event-date">
                <?=
                // WARNING : formatLocalized() fonctionne bien sous UNIX en production mais ne remonte pas avant le 01/01/1970 sous windows
                ucfirst(utf8_encode(hDate::formatLocalized($entry->event_date, '%d %B %Y')));
                ?>
            </div>
            <div>
                <?php if ($entry->hasImage() & file_exists($entry->getImagePath('std', true))): ?>
                    <?= Html::img($entry->getImageUrl('std'), ['class' => 'img-responsive img-thumbnail', 'alt' => $entry->getSlug()]) ?>
                <?php else : ?>
                    &nbsp;-&nbsp;
                <?php endif ?>
            </div>
        </div>

        <div class="col-sm-8">
            <div>
                <h2>
                    <?= Html::a('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp;' . Html::encode($entry->title),
                        Url::to(['/calendar-entries/show', 'id' => $entry->id, 'slug' => $entry->getSlug()])
                    ) ?>
                </h2>
            </div>

            <div>
                <div class="calendar-entry-text">
                    <?= nl2br($entry->body) ?>
                </div>

                <?= $this->render('_tagsButtons', ['model' => $entry]) ?>

            </div>
        </div>

    </div>

</div>

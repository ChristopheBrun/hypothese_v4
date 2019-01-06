<?php
/**
 * Affiche une liste de boutons pour les tags du modèle
 *
 * @var CalendarEntry $model
 */

use app\modules\ephemerides\models\CalendarEntry;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php if (count($model->tags)) : ?>
    <div class="row calendar-entry-tags">
        <?php foreach ($model->tags as $tag) : ?>
            <?= Html::a(Html::encode($tag->label),
                Url::to(['/calendar-entries/post-search', 'tag' => $tag->id]), ['class' => 'btn btn-primary'])
//                Url::to(['/calendar-entries/list', 'tagId' => $tag->id, 'tag' => $tag->getSlug(), 'page' => 1]), ['class' => 'btn btn-primary'])
            ?>
        <?php endforeach ?>
    </div>
<?php endif ?>

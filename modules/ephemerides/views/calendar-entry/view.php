<?php /** @noinspection PhpUnhandledExceptionInspection */

use app\modules\ephemerides\EphemeridesModule;
use app\modules\ephemerides\models\CalendarEntry;
use app\modules\hlib\helpers\AssetsHelper;
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\DisplayModels;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $model CalendarEntry */

$this->title = $model->title;

$this->params['breadcrumbs'][] = ['label' => EphemeridesModule::t('labels', 'Calendar Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="calendar-entry-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(HLib::t('labels', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(HLib::t('labels', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => HLib::t('labels', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a("Fiche frontend", ['show', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'event_date',
            'title',
            [
                'attribute' => 'tags',
                'value' => DisplayModels::widget([
                    'models' => $model->tags,
                    'labelField' => 'label',
                ]),
                'format' => 'html',
            ],
            'description:html',
            'twitter',
            [
                'label' => "URL fiche frontend",
                'value' => Url::to(['/ephemerides/calendar-entry/show', 'id' => $model->id], true),
                'format'=> 'url',
            ],
            'notes:html',
            'body:html',
            [
                'attribute' => 'image',
                'value' => Html::img($model->getImageUrl('xs', 'true')),
                'format' => 'html',
            ],
            'image_caption',
            'source_image',
            AssetsHelper::detailViewSeparator(),
            [
                'attribute' => 'enabled',
                'value' => AssetsHelper::getImageTagForBoolean($model->enabled),
                'format' => 'html',
            ],
            'id',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

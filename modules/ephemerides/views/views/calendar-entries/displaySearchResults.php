<?php
/**
 * Liste des éphémérides de l'application, filtrée sur le tag (frontend)
 */
use app\models\CalendarEntry;
use app\models\CalendarEntrySearchForm;
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\GridListHeader;
use Carbon\Carbon;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var yii\web\View            $this
 * @var ActiveDataProvider      $dataProvider
 * @var string                  $sortClausesSessionKey
 * @var app\models\Tag          $filteringTag
 * @var CalendarEntrySearchForm $searchModel
 * @var array                   $tags [Tag]
 */

$this->title = Yii::t('labels', 'Calendar Entries list');
$count = $dataProvider->getTotalCount();

/** @var array $models */
/** @var app\models\CalendarEntry $model */
$models = $dataProvider->getModels();

?>
<div class="row panel panel-default">

    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">

        <div class="row">
            <div class="col-sm-12">
                <?= $this->render('/site/_frontendSearchForm', ['model' => $searchModel, 'tags' => $tags]) ?>
            </div>
        </div>

        <?php if ($count) :
            LinkPager::widget(['pagination' => $dataProvider->getPagination()])
            ?>

            <ul class="list-group">
                <?= GridListHeader::widget([
                    'sortAction' => '/calendar-entries/display-search-results-sort',
                    'sortClausesSessionKey' => $sortClausesSessionKey,
                    'columns' => [
                        ['width' => 2, 'label' => HLib::t('labels', 'Date'), 'orderBy' => 'event_date', 'sortIcon' => 'order'],
                        ['width' => 1, 'label' => ' '],
                        ['width' => 6, 'label' => HLib::t('labels', 'Title'), 'orderBy' => 'title'],
                        ['width' => 3, 'label' => Yii::t('labels', 'Tags')],
                    ],
                ]) ?>

                <?php foreach ($models as $model): ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-2 <?= $model->enabled ? '' : 'disabled' ?>">
                                <?= Carbon::parse($model->event_date)->format(CalendarEntry::DATE_FORMAT_DAY) ?>
                            </div>

                            <div class="col-sm-1">
                                <?= Html::img($model->getImageUrl('xs', true)) ?>
                            </div>

                            <div class="col-sm-6">
                                <?= Html::a($model->title, Url::to(['/calendar-entries/show', 'id' => $model->id, 'slug' => $model->getSlug()])) ?>
                            </div>

                            <div class="col-sm-3">
                                <?= implode(', ', $model->getTagsNames()) ?>
                            </div>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>

            <?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
        <?php endif ?>

    </div>

    <div class="panel-footer">
        <!--suppress HtmlUnknownTag -->
        <info>
            <?= HLib::t('messages',
                'There {n, plural, =0{are no results} =1{is one results} other{are # results} fitting your search criteria}',
                ['n' => $count]) ?>
        </info>
        <div class="text-center">
            <?= Html::a('<span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp' . HLib::t('labels', 'Home'),
                Url::to(['/site/index'])) ?>
        </div>
    </div>

</div>

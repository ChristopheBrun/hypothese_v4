<?php
/**
 * Liste des éphémérides de l'application (backend)
 */

use app\models\CalendarEntry;
use app\models\CalendarEntrySearchForm;
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\DeleteLink;
use app\modules\hlib\widgets\GridListHeader;
use Carbon\Carbon;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var yii\web\View            $this
 * @var CalendarEntrySearchForm $searchModel
 * @var ActiveDataProvider      $dataProvider
 * @var array                   $tags [id => label]
 * @var string                  $sortClausesSessionKey
 */

$this->title = Yii::t('labels', 'Calendar Entries list');
$count = $dataProvider->getTotalCount();

/** @var array $models */
/** @var CalendarEntry $model */
$models = $dataProvider->getModels();

?>
<div class="row panel panel-default">

    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">

        <?php // En-tête de la liste & moteur de recherche ?>
        <div class="row global-actions">
            <div class="col-sm-6">
                <a class="btn btn-primary" href="<?= Url::toRoute('/calendar-entries/register-filters/all') ?>">
                    <?= HLib::t('labels', 'All') ?>
                </a>
                <a class="btn btn-info" href="<?= Url::toRoute('/calendar-entries/register-filters/today') ?>">
                    <?= Yii::t('labels', 'Today') ?>
                </a>
                <a class="btn btn-info" href="<?= Url::toRoute('/calendar-entries/register-filters/tomorrow') ?>">
                    <?= Yii::t('labels', 'Tomorrow') ?>
                </a>
                <a id="advanced-search-btn" class="btn btn-default" href="#">
                    <?= HLib::t('labels', 'Advanced search') ?>
                </a>
            </div>
            <div class="col-sm-6 no-padding-right">
                <a class="btn btn-success pull-right" href="<?= Url::toRoute('/calendar-entries/create') ?>">
                    <?= Yii::t('labels', 'Create a new calendar entry') ?>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 filters-string">
                <?= $searchModel->hasActiveFilters() ? Yii::t('labels', "Filters") . ' : ' . $searchModel->displayActiveFilters() : '' ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <info>
                    <?= HLib::t('messages',
                        'There {n, plural, =0{are no results} =1{is one results} other{are # results} fitting your search criteria}',
                        ['n' => $count]) ?>
                </info>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <?= $this->render('_backendSearchForm', ['model' => $searchModel, 'tags' => $tags]) ?>
            </div>
        </div>

        <?php
        // liste des modèles
        if ($count) :
            LinkPager::widget(['pagination' => $dataProvider->getPagination()])
            ?>

            <ul id="models-list" class="list-group">
                <?= GridListHeader::widget([
                    'sortAction' => '/calendar-entries/index-sort',
                    'sortClausesSessionKey' => $sortClausesSessionKey,
                    'columns' => [
                        ['width' => 1, 'label' => HLib::t('labels', 'Date'), 'orderBy' => 'event_date', 'sortIcon' => 'order'],
                        ['width' => 1, 'label' => ' '],
                        ['width' => 4, 'label' => HLib::t('labels', 'Title'), 'orderBy' => 'title'],
                        ['width' => 3, 'label' => Yii::t('labels', 'Tags')],
                        ['width' => 1, 'label' => HLib::t('labels', 'Upd. at'), 'orderBy' => 'updated_at', 'sortIcon' => 'order'],
                        ['width' => 2, 'label' => HLib::t('labels', 'Actions'), 'cssClass' => 'object-actions text-right'],
                    ],
                ]) ?>

                <?php foreach ($models as $model): ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-1 <?= $model->enabled ? '' : 'disabled' ?>">
                                <?= Carbon::parse($model->event_date)->format(CalendarEntry::DATE_FORMAT_DAY) ?>
                            </div>

                            <div class="col-sm-1">
                                <?= Html::img($model->getImageUrl('xs', true)) ?>
                            </div>

                            <div class="col-sm-4">
                                <?= Html::a($model->title, Url::to(['/calendar-entries/update', 'id' => $model->id])) ?>
                            </div>

                            <div class="col-sm-3">
                                <?= implode(', ', $model->getTagsNames()) ?>
                            </div>

                            <div class="col-sm-1">
                                <?= Carbon::parse($model->updated_at)->format(CalendarEntry::DATE_FORMAT_DAY) ?>
                            </div>

                            <div class="object-actions col-sm-2 text-right">
                                <?= $this->render('@app/modules/hlib/views/partials/_editLink',
                                    ['url' => Url::to(['/calendar-entries/update', 'id' => $model->id])]) ?>

                                <?= $this->render('@app/modules/hlib/views/partials/_showLink',
                                    ['url' => Url::to(['/calendar-entries/view', 'id' => $model->id])]) ?>

                                <?= DeleteLink::widget([
                                    'url' => Url::to(['/calendar-entries/delete', 'id' => $model->id], true),
                                    'data' => $model->title,
                                ]) ?>
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
    </div>

</div>

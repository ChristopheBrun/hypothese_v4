<?php
/**
 * Liste des tags de l'application
 */
use app\models\Tag;
use app\models\TagSearchForm;
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\DeleteLink;
use app\modules\hlib\widgets\GridListHeader;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var yii\web\View       $this
 * @var TagSearchForm      $searchModel
 * @var ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('labels', 'Tags List');
$count = $dataProvider->getTotalCount();

$sortClausesSessionKey = Tag::class . '.sort';
$sortClauses = Yii::$app->session->get(Tag::class . '.sort');

/** @var array $models */
/** @var Tag $model */
$models = $dataProvider->getModels();
?>
<div class="row panel panel-default">

    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">

        <?php // En-tête de la liste & moteur de recherche ?>
        <div class="row global-actions">
            <div class="col-sm-2">
                <a id="advanced-search-btn" class="btn btn-default" href="#" role="button"><?= HLib::t('labels', 'Advanced search') ?></a>
            </div>
            <div class="col-sm-8 filters-string">
                <?= $searchModel->hasActiveFilters() ? $searchModel->displayActiveFilters() : '' ?>
            </div>
            <div class="col-sm-2">
                <a class="btn btn-success" href="<?= Url::toRoute('/tags/create') ?>" role="button">
                    <?= Yii::t('labels', 'Create a new tag') ?>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <?= $this->render('_search', ['model' => $searchModel]) ?>
            </div>
        </div>

        <?php
        // liste des modèles
        if ($count) :
            LinkPager::widget(['pagination' => $dataProvider->getPagination()])
            ?>

            <ul class="list-group">
                <?= GridListHeader::widget([
                    'sortAction' => '/tags/sort',
                    'sortClausesSessionKey' => $sortClausesSessionKey,
                    'columns' => [
                        ['width' => 9, 'label' => HLib::t('labels', 'Label'), 'orderBy' => 'label', 'sortIcon' => 'order'],
                        ['width' => 1, 'label' => Yii::t('labels', 'Calendar entries')],
                        ['width' => 2, 'label' => HLib::t('labels', 'Actions'), 'cssClass' => 'object-actions text-right'],
                    ],
                ]) ?>

                <?php foreach ($models as $model): ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-9">
                                <?= Html::a($model->label, Url::to(['/tags/update', 'id' => $model->id])) ?>
                            </div>

                            <div class="col-sm-1">
                                <?= count($model->calendarEntries) ?>
                            </div>

                            <div class="object-actions col-sm-2 text-right">
                                <?= $this->render('@app/modules/hlib/views/partials/_editLink',
                                    ['url' => Url::to(['/tags/update', 'id' => $model->id], true)]) ?>

                                <?= $this->render('@app/modules/hlib/views/partials/_showLink',
                                    ['url' => Url::to(['/tags/view', 'id' => $model->id], true)]) ?>

                                <?= DeleteLink::widget([
                                    'url' => Url::to(['/tags/delete', 'id' => $model->id], true),
                                    'data' => $model->label,
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

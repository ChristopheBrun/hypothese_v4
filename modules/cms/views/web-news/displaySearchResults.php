<?php
/**
 * Liste des actualités de l'application, filtrée sur le tag (frontend)
 */
use app\modules\cms\HCms;
use app\modules\cms\models\forms\WebNewsSearch;
use app\modules\cms\models\WebNews;
use app\modules\cms\models\WebTag;
use app\modules\cms\widgets\TagsButtons;
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\GridListHeader;
use Carbon\Carbon;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var yii\web\View       $this
 * @var ActiveDataProvider $dataProvider
 * @var string             $sortClausesSessionKey
 * @var WebTag             $filteringTag
 * @var WebNewsSearch      $searchModel
 * @var array              $tags [Tag]
 */

$this->title = HCms::t('labels', 'News list');
$this->params['breadcrumbs'][] = HCms::t('labels', 'Web news');
$this->registerMetaTag(['description' => HCms::t('labels', $this->title)]);

$count = $dataProvider->getTotalCount();

/**
 * @var array   $models
 * @var WebNews $model
 */

$models = $dataProvider->getModels();

?>
<div class="row">
    <div class="col-sm-12">
        <?= $this->render('/web-news/_frontendSearchForm', ['model' => $searchModel, 'tags' => $tags]) ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <?php if ($count) :
            LinkPager::widget(['pagination' => $dataProvider->getPagination()])
            ?>

            <ul class="list-group">
                <?= GridListHeader::widget([
                    'sortAction' => '/cms/web-news/display-search-results-sort',
                    'sortClausesSessionKey' => $sortClausesSessionKey,
                    'columns' => [
                        ['width' => 2, 'label' => HLib::t('labels', 'Date'), 'orderBy' => 'base_news.event_date', 'sortIcon' => 'order'],
                        ['width' => 7, 'label' => HLib::t('labels', 'Title'), 'orderBy' => 'title'],
                        ['width' => 3, 'label' => Yii::t('labels', 'Tags')],
                    ],
                ]) ?>

                <?php foreach ($models as $model): ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-2 <?= $model->base->enabled ? '' : 'disabled' ?>">
                                <?= Carbon::parse($model->base->event_date)->format(WebNews::DATE_FORMAT_DAY) ?>
                            </div>

                            <div class="col-sm-7">
                                <?= Html::a($model->title, Url::to(['/cms/web-news/show', 'id' => $model->id, 'slug' => $model->getSlug()])) ?>
                            </div>

                            <div class="col-sm-3">
                                <?= TagsButtons::widget(['tags' => $model->getTags(), 'buttonSize' => 'xs']) ?>
                            </div>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>

            <?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
        <?php endif ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <!--suppress HtmlUnknownTag -->
        <info>
            <?= HLib::t('messages',
                'There {n, plural, =0{are no results} =1{is one results} other{are # results} fitting your search criteria}',
                ['n' => $count]) ?>
        </info>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <hr/>
        <div class="text-center">
            <?= Html::a('<span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp' . HLib::t('labels', 'Home'),
                Url::to(['/site/index'])) ?>
        </div>
    </div>
</div>

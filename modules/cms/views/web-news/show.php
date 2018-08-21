<?php
/**
 * Fiche de consultation d'un objet (frontend)
 */

use app\modules\cms\HCms;
use app\modules\cms\models\WebNews;
use app\modules\cms\widgets\TagsButtons;
use app\modules\hlib\HLib;
use Carbon\Carbon;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var WebNews      $model
 */

$this->title = $model->title;
$this->registerMetaTag(['description' => $model->description]);
$this->params['breadcrumbs'][] = HCms::t('labels', 'Web news');
$this->params['breadcrumbs'][] = $model->base->event_date;

?>
<div class="row">
    <div class="col-sm-12">
        <div class="news">

            <div class="row">
                <div class="col-sm-1">
                    <?= Html::img(
                        $this->assetManager->getBundle('app\modules\cms\assets\CmsAsset')->baseUrl . '/calendrier-globe-32.png',
                        ['alt' => 'date']
                    ) ?>
                </div>
                <div class="col-sm-3 event-date">
                    <?= Carbon::createFromFormat('Y-m-d', $model->base->event_date)->formatLocalized('%d/%m/%Y') ?>
                </div>
                <div class="col-sm-8 text-right">
                    <?= TagsButtons::widget(['tags' => $model->getTags()]) ?>
                </div>
            </div>

            <hr/>

            <div class="row">
                <div class="col-sm-12 content">
                    <?= $model->body ?>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="row news-navigation-links">
    <div class="col-sm-4">
        <?php if ($previous = $model->getPrevious()) : ?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>&nbsp' . HCms::t('labels', 'Previous news'),
                Url::to(['/cms/web-news/show', 'id' => $previous->id, 'slug' => $previous->getSlug()])
            ) ?>
        <?php endif ?>
    </div>

    <div class="col-sm-4 text-center">
        <?= Html::a(
            '<span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp' . HLib::t('labels', 'Home'),
            Url::to(['/site/index'])
        ) ?>
    </div>

    <div class="col-sm-4 text-right">
        <?php if ($next = $model->getNext()) : ?>
            <?= Html::a(
                HCms::t('labels', 'Next news') . '&nbsp<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>',
                Url::to(['/cms/web-news/show', 'id' => $next->id, 'slug' => $next->getSlug()])
            ) ?>
        <?php endif ?>
    </div>
</div>


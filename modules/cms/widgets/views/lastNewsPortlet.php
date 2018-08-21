<?php

/**
 * Affiche un résumé simple (texte non formatté en principe) d'une actualité
 *
 * @var array  $models [WebNews]
 * @var int    $maxLength
 * @var string $suffix
 */

use app\modules\cms\HCms;
use app\modules\cms\models\WebNews;
use Carbon\Carbon;
use yii\bootstrap\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * @var WebNews $model
 */

?>
<div class="lastNewsPortlet">
    <h2><?= HCms::t('labels', 'Last news') ?></h2>
    <?php foreach ($models as $model) : ?>
        <div class="row">
            <div class="col-sm-1">
                <?= Html::img($this->assetManager->getBundle('app\modules\cms\assets\CmsAsset')->baseUrl . '/calendrier-globe-16.png', ['alt' => 'date']) ?>
            </div>
            <div class="col-sm-10 event-date">
                <?= Carbon::createFromFormat('Y-m-d', $model->base->event_date)->formatLocalized('%d/%m/%Y') ?>
            </div>
        </div>
        <div class="row">
            <div class=" col-sm-12 title">
                <?= Html::a(Html::encode($model->title), Url::to(['/cms/web-news/show', 'id' => $model->id, 'slug' => $model->getSlug()])) ?>
            </div>
        </div>
        <div class="row">
            <div class=" col-sm-12 text">
                <?= StringHelper::truncateWords(strip_tags($model->body), $maxLength, $suffix) ?>
                <hr/>
            </div>
        </div>
    <?php endforeach ?>
</div>

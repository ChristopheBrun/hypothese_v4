<?php

/**
 * Affiche un résumé simple (texte non formatté en principe) d'une actualité
 *
 * @var array  $models [WebNews]
 * @var int    $maxLength
 * @var string $suffix
 */

use app\modules\cms\models\WebNews;
use Carbon\Carbon;
use yii\bootstrap\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var WebNews $model
 * @var View    $this
 */

$view = $this->context;
foreach ($models as $model) :
    $showUrl = Url::to(['/cms/web-news/show', 'id' => $model->id, 'slug' => $model->getSlug()]);
    $suffix = Html::a($suffix, $showUrl);
    ?>
    <div class="news">
        <div class="row">
            <div class="col-sm-1">
                <?= Html::img(
                    $this->assetManager->getBundle(
                        'app\modules\cms\assets\CmsAsset')->baseUrl . '/calendrier-globe-32.png',
                    ['alt' => 'date']
                ) ?>
            </div>
            <div class="col-sm-2 event-date">
                <?= Carbon::createFromFormat('Y-m-d', $model->base->event_date)->formatLocalized('%d/%m/%Y') ?>
            </div>
            <div class="col-sm-9">
                <h2>
                    <?= Html::a(Html::encode($model->title), $showUrl) ?>
                </h2>
            </div>
        </div>
        <div class="row">
            <hr/>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?= StringHelper::truncateWords($model->body, $maxLength, $suffix, true) ?>
            </div>
        </div>
    </div>
<?php endforeach ?>

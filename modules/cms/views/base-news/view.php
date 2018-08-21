<?php
/**
 * Fiche de consultation d'un objet (backend)
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BaseNews;
use app\modules\cms\models\WebNews;
use app\modules\hlib\helpers\hAssets;
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\ViewButtons;
use app\modules\hlib\widgets\ViewModelsList;
use Carbon\Carbon;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var BaseNews     $model
 */

$this->title = HCms::t('labels', 'View base news');

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= $this->title ?></h1>
        <h2><?= Html::encode($model->event_date) ?></h2>
    </div>

    <div class="panel-body">

        <?= ViewButtons::widget([
            'modelId' => $model->id,
            'controllerPath' => '/cms/base-news',
        ]) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => HCms::t('labels', 'Event date'),
                    'value' => utf8_encode(Carbon::createFromFormat('Y-m-d', $model->event_date)->formatLocalized('%A %d %B %Y')),
                ],
            ],
        ]) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => HCms::t('labels', 'Web news'),
                    'value' => ViewModelsList::widget([
                        'models' => $model->webNews, 'controllerRoute' => '/cms/web-news',
                        'labelCallback' => function (WebNews $news) {
                            $lang = $news->language->iso_639_code;
                            return "[$lang]&nbsp;&nbsp;" . $news->title;
                        },
                        'listType' => 'div',
                    ]),
                    'format' => 'html',
                ],
            ],
        ]) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => HLib::t('labels', 'Enabled'),
                    'value' => hAssets::getImageTagForBoolean($model->enabled),
                    'format' => 'html',
                ],
                'created_at',
                'updated_at',
            ],
        ]) ?>

    </div>
</div>

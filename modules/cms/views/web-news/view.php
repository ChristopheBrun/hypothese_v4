<?php
/**
 * Fiche de consultation d'un objet (backend)
 */

use app\modules\cms\HCms;
use app\modules\cms\models\WebNews;
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\ViewButtons;
use app\modules\hlib\widgets\ViewModelsList;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var WebNews      $model
 */

$this->title = HCms::t('labels', 'View news');

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($model->title) ?></h1>
    </div>

    <div class="panel-body">

        <?= ViewButtons::widget([
            'modelId' => $model->id,
            'controllerPath' => '/cms/web-news',
        ]) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'title',
                'description',
                [
                    'label' => HCms::t('label', 'Body'),
                    'value' => $model->body,
                    'format' => 'html',
                ]
            ],
        ]) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => HLib::t('labels', 'Enabled'),
                    'value' => \app\modules\hlib\helpers\hAssets::getImageTagForBoolean($model->isEnabled()),
                    'format' => 'html',
                ],
                [
                    'label' => HCms::t('labels', 'Base news'),
                    'value' => ViewModelsList::widget([
                        'models' => $model->base,
                        'labelField' => 'event_date',
                        'controllerRoute' => '/cms/base-news',
                        'listType' => 'div',
                    ]),
                    'format' => 'html',
                ],
                [
                    'label' => HCms::t('labels', 'Language'),
                    'value' => $model->language->iso_639_code,
                ],
            ],
        ]) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'created_at',
                'updated_at',
            ],
        ]) ?>

    </div>
</div>

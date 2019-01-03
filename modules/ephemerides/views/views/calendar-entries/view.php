<?php
/**
 * Fiche de consultation d'un objet (backend)
 */

use app\modules\hlib\helpers\hAssets;
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\ViewButtons;
use app\modules\hlib\widgets\ViewModelsList;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var $this yii\web\View
 * @var $model app\models\CalendarEntry
 */
$this->title = Yii::t('labels', 'View calendar entry') . ' : ' . $model->title;
?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">

        <?= ViewButtons::widget([
                'modelId' => $model->id,
                'controllerPath' => '/calendar-entries',
                'indexPage' => 1,
                'additionalButtons' => [
                    [
                        'label' => Yii::t('labels', 'Show model (frontend)'),
                        'class' => 'btn-default',
                        'url' => Url::to(['calendar-entries/show', 'slug' => $model->getSlug(), 'id' => $model->id])
                    ],
                ]
            ]
        ) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'title',
                [
                    'label' => Yii::t('labels', 'Body'),
                    'value' => nl2br($model->body),
                    'format' => 'html',
                ],
                [
                    'label' => Yii::t('labels', 'Image'),
                    'value' => Html::img($model->getImageUrl(null, true), ['alt' => $model->getSlug()]),
                    'format' => 'html',
                ],
                'image_caption',
                [
                    'label' => Yii::t('labels', 'Tags'),
                    'value' => ViewModelsList::widget([
                        'models' => $model->tags, 'labelField' => 'label', 'controllerRoute' => '/tags'
                    ]),
                    'format' => 'html',
                ],
                [
                    'label' => Yii::t('labels', 'Articles'),
                    'value' => ViewModelsList::widget([
                        'models' => $model->articles, 'labelField' => 'title', 'controllerRoute' => '/articles', 'checkEnabled' => true
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

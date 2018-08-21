<?php

/**
 * Liste des modèles (backend)
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BaseNews;
use app\modules\cms\models\WebNews;
use app\modules\hlib\helpers\hAssets;
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\GridListActionButtons;
use app\modules\hlib\widgets\GridListHeader;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var yii\web\View       $this
 * @var ActiveDataProvider $dataProvider
 * @var array              $emptyBaseNews [BaseNews]
 */

$this->title = HCms::t('labels', 'Web news list');
$count = $dataProvider->getTotalCount();

/**
 * @var array    $models
 * @var WebNews  $model
 * @var BaseNews $baseModel
 */

$models = $dataProvider->getModels();

?>
<div class="row panel panel-default">

    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">

        <div class="global-actions text-right">
            <a class="btn btn-success" href="<?= Url::toRoute('/cms/base-news/create') ?>" role="button">
                <?= HCms::t('labels', 'Create a new base news') ?>
            </a>

            <a class="btn btn-success" href="<?= Url::toRoute('/cms/web-news/create') ?>" role="button">
                <?= HCms::t('labels', 'Create a new web news') ?>
            </a>
        </div>

        <?php if (count($emptyBaseNews)) : ?>
            <ul class="list-group">
                <?= GridListHeader::widget(['columns' => [
                    ["width" => 1, "label" => HLib::t('labels', 'Enabled')],
                    ["width" => 4, "label" => HCms::t('labels', 'Base news without related news')],
                    ["width" => 5, "label" => HCms::t('labels', 'Tags')],
                    ["width" => 2, "label" => HLib::t('labels', 'Actions'), "cssClass" => "object-actions text-right"],
                ]]) ?>

                <?php foreach ($emptyBaseNews as $baseModel): ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-1">
                                <?= hAssets::getImageTagForBoolean($baseModel['enabled']) ?>
                            </div>

                            <div class="col-sm-4">
                                <?= Html::a($baseModel['event_date'], Url::to(['/cms/base-news/update', 'id' => $baseModel['id']])) ?>
                            </div>

                            <div class="col-sm-5">
                                <?= implode(',', $baseModel->baseTagsCodes()) ?>
                            </div>

                            <div class="object-actions col-sm-2 text-right">
                                <?= GridListActionButtons::widget([
                                    'controllerRoute' => '/cms/base-news',
                                    'modelId' => $baseModel['id'],
                                    'deleteMessageData' => $baseModel['event_date'],
                                ]) ?>
                            </div>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>

        <?php if ($count) : ?>
            <?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>

            <ul class="list-group">
                <?= GridListHeader::widget(['columns' => [
                    ["width" => 1, "label" => HLib::t('labels', 'Date')],
                    ["width" => 1, "label" => HCms::t('labels', 'Language')],
                    ["width" => 5, "label" => HLib::t('labels', 'Title')],
                    ["width" => 3, "label" => HLib::t('labels', 'Tags')],
                    ["width" => 2, "label" => HLib::t('labels', 'Actions'), "cssClass" => "object-actions text-right"],
                ]]) ?>

                <?php foreach ($models as $model): ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-1 <?= $model->isEnabled() ? '' : 'disabled' ?>">
                                <?= $model->base->event_date ?>
                            </div>

                            <div class="col-sm-1">
                                <?= $model->language->iso_639_code ?>
                            </div>

                            <div class="col-sm-5">
                                <?= Html::a($model->title, Url::to(['/cms/web-news/view', 'id' => $model->id])) ?>
                            </div>

                            <div class="col-sm-3">
                                <?= implode(', ', $model->getTagsNames()) ?>
                            </div>

                            <div class="object-actions col-sm-2 text-right">
                                <?= GridListActionButtons::widget([
                                    'controllerRoute' => '/cms/web-news',
                                    'modelId' => $model->id,
                                    'deleteMessageData' => $model->title,
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

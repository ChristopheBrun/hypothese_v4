<?php
/**
 * Liste des modèles
 */
use app\modules\cms\HCms;
use app\modules\cms\models\WebTag;
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
 * @var array              $emptyBaseTags []
 */

$this->title = HCms::t('labels', 'Web tags list');
$count = $dataProvider->getTotalCount();

/**
 * @var array  $models [WebTag]
 * @var WebTag $model
 */
$models = $dataProvider->getModels();
?>
<div class="row panel panel-default">

    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">

        <div class="global-actions text-right">
            <a class="btn btn-success" href="<?= Url::toRoute('/cms/base-tags/create') ?>" role="button">
                <?= HCms::t('labels', 'Create a new base tag') ?>
            </a>

            <a class="btn btn-success" href="<?= Url::toRoute('/cms/web-tags/create') ?>" role="button">
                <?= HCms::t('labels', 'Create a new tag') ?>
            </a>
        </div>

        <?php if (count($emptyBaseTags)) : ?>
            <ul class="list-group">
                <?= GridListHeader::widget(['columns' => [
                    ["width" => 10, "label" => HCms::t('labels', 'Base tags without related tags')],
                    ["width" => 2, "label" => HLib::t('labels', 'Actions'), "cssClass" => "object-actions text-right"],
                ]]) ?>

                <?php foreach ($emptyBaseTags as $baseModel): ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-10">
                                <?= $baseModel['code'] ?>
                            </div>

                            <div class="object-actions col-sm-2 text-right">
                                <?= GridListActionButtons::widget([
                                    'controllerRoute' => '/cms/base-tags',
                                    'modelId' => $baseModel['id'],
                                    'deleteMessageData' => $baseModel['code'],
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
                    ["width" => 2, "label" => HCms::t('labels', 'Base tag')],
                    ["width" => 1, "label" => HLib::t('labels', 'Language')],
                    ["width" => 6, "label" => HLib::t('labels', 'Label')],
                    ["width" => 1, "label" => HLib::t('labels', 'Nb ref.')],
                    ["width" => 2, "label" => HLib::t('labels', 'Actions'), "cssClass" => "object-actions text-right"],
                ]]) ?>

                <?php foreach ($models as $model): ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-2">
                                <?= $model->base->code ?>
                            </div>

                            <div class="col-sm-1">
                                <?= $model->language->iso_639_code ?>
                            </div>

                            <div class="col-sm-6">
                                <?= Html::a($model->label, Url::to(['/cms/web-tags/view', 'id' => $model->id])) ?>
                            </div>

                            <div class="col-sm-1">
                                <?= count($model->getReferers()) ?>
                            </div>

                            <div class="object-actions col-sm-2 text-right">
                                <?= GridListActionButtons::widget([
                                    'controllerRoute' => '/cms/web-tags',
                                    'modelId' => $model->id,
                                    'deleteMessageData' => $model->label,
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

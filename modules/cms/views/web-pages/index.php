<?php
/**
 * Liste des pages web de l'application (backend)
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BasePage;
use app\modules\cms\models\WebPage;
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
 * @var array              $emptyBasePages []
 *
 * @var array              $models
 * @var WebPage            $model
 * @var BasePage           $baseModel
 */

$this->title = HCms::t('labels', 'Pages list');
$count = $dataProvider->getTotalCount();
$models = $dataProvider->getModels();

?>
<div class="row panel panel-default">

    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">

        <div class="global-actions text-right">
            <a class="btn btn-default" href="<?= Url::toRoute('/cms/base-pages/reset-menu-indexes') ?>" role="button">
                <?= HCms::t('labels', 'Reset menu indexes') ?>
            </a>

            <a class="btn btn-success" href="<?= Url::toRoute('/cms/base-pages/create') ?>" role="button">
                <?= HCms::t('labels', 'Create a new base page') ?>
            </a>

            <a class="btn btn-success" href="<?= Url::toRoute('/cms/web-pages/create') ?>" role="button">
                <?= HCms::t('labels', 'Create a new page') ?>
            </a>
        </div>

        <?php if (count($emptyBasePages)) : ?>
            <ul class="list-group">
                <?= GridListHeader::widget(['columns' => [
                    'status' => ["width" => 1, "label" => HLib::t('labels', 'Enabled')],
                    'code' => ["width" => 9, "label" => HCms::t('labels', 'Base pages without related pages')],
                    'actions' => ["width" => 2, "label" => HLib::t('labels', 'Actions'), "cssClass" => "object-actions text-right"],
                ]]) ?>

                <?php foreach ($emptyBasePages as $baseModel): ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-1">
                                <?= hAssets::getImageTagForBoolean($baseModel['enabled']) ?>
                            </div>

                            <div class="col-sm-9">
                                <?= $baseModel['code'] ?>
                            </div>

                            <div class="object-actions col-sm-2 text-right">
                                <?= GridListActionButtons::widget([
                                    'controllerRoute' => '/cms/base-pages',
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
                    ["width" => 1, "label" => HLib::t('labels', 'Enabled')],
                    ["width" => 1, "label" => HCms::t('labels', 'Base')],
                    ["width" => 1, "label" => HCms::t('labels', 'Language')],
                    ["width" => 4, "label" => HLib::t('labels', 'Title')],
                    ["width" => 3, "label" => HCms::t('labels', 'Menu')],
                    ["width" => 2, "label" => HLib::t('labels', 'Actions'), "cssClass" => "object-actions text-right"],
                ]]) ?>

                <?php foreach ($models as $model): ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-1">
                                <?= hAssets::getImageTagForBoolean($model->base->enabled) ?>
                            </div>

                            <div class="col-sm-1">
                                <?= $model->base->code ?>
                            </div>

                            <div class="col-sm-1">
                                <?= $model->language->iso_639_code ?>
                            </div>

                            <div class="col-sm-4">
                                <?= Html::a($model->title, Url::to(['/cms/web-pages/view', 'id' => $model->id])) ?>
                            </div>

                            <div class="col-sm-3">
                                <?= $this->render('_menuIndexManagement', ['model' => $model]) ?>
                            </div>

                            <div class="object-actions col-sm-2 text-right">
                                <?= GridListActionButtons::widget([
                                    'controllerRoute' => '/cms/web-pages',
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

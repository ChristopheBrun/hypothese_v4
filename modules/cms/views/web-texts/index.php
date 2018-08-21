<?php
/**
 * Liste des modèles
 */
use app\modules\cms\HCms;
use app\modules\cms\models\BaseText;
use app\modules\cms\models\WebText;
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
 * @var array              $emptyBaseTexts []
 */

$this->title = HCms::t('labels', 'Web texts list');
$count = $dataProvider->getTotalCount();

/**
 * @var array    $models [WebText]
 * @var WebText  $model
 * @var BaseText $baseModel
 */
$models = $dataProvider->getModels();
?>
<div class="row panel panel-default">

    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">

        <div class="global-actions text-right">
            <a class="btn btn-success" href="<?= Url::toRoute('/cms/base-texts/create') ?>" role="button">
                <?= HCms::t('labels', 'Create a new base text') ?>
            </a>

            <a class="btn btn-success" href="<?= Url::toRoute('/cms/web-texts/create') ?>" role="button">
                <?= HCms::t('labels', 'Create a new text') ?>
            </a>
        </div>

        <?php if (count($emptyBaseTexts)) : ?>
            <ul class="list-group">
                <?= GridListHeader::widget(['columns' => [
                    ["width" => 2, "label" => HCms::t('labels', 'Base page')],
                    ["width" => 8, "label" => HCms::t('labels', 'Base texts without related texts')],
                    ["width" => 2, "label" => HLib::t('labels', 'Actions'), "cssClass" => "object-actions text-right"],
                ]]) ?>

                <?php foreach ($emptyBaseTexts as $baseModel): ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-2">
                                <?= $baseModel->basePage->code ?>
                            </div>

                            <div class="col-sm-8">
                                <?= $baseModel->code ?>
                            </div>

                            <div class="object-actions col-sm-2 text-right">
                                <?= GridListActionButtons::widget([
                                    'controllerRoute' => '/cms/base-texts',
                                    'modelId' => $baseModel->id,
                                    'deleteMessageData' => $baseModel->code,
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
                    ["width" => 2, "label" => HCms::t('labels', 'Base page')],
                    ["width" => 2, "label" => HCms::t('labels', 'Base text')],
                    ["width" => 1, "label" => HCms::t('labels', 'Language')],
                    ["width" => 5, "label" => HLib::t('labels', 'Title')],
                    ["width" => 2, "label" => HLib::t('labels', 'Actions'), "cssClass" => "object-actions text-right"],
                ]]) ?>

                <?php foreach ($models as $model): ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-2">
                                <?= $model->base->basePage->code ?>
                            </div>

                            <div class="col-sm-2">
                                <?= $model->base->code ?>
                            </div>

                            <div class="col-sm-1">
                                <?= $model->language->iso_639_code ?>
                            </div>

                            <div class="col-sm-5">
                                <?= Html::a($model->title, Url::to(['/cms/web-texts/view', 'id' => $model->id])) ?>
                            </div>

                            <div class="object-actions col-sm-2 text-right">
                                <?= GridListActionButtons::widget([
                                    'controllerRoute' => '/cms/web-texts',
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

<?php

/**
 * Liste des langues de l'application
 */

use app\modules\cms\HCms;
use app\modules\cms\models\Language;
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\DeleteLink;
use app\modules\hlib\widgets\GridListActionButtons;
use app\modules\hlib\widgets\GridListHeader;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var yii\web\View       $this
 * @var ActiveDataProvider $dataProvider
 */

$this->title = HCms::t('labels', 'Languages list');
$count = $dataProvider->getTotalCount();

/** @var array $models */
/** @var Language $model */
$models = $dataProvider->getModels();
?>
<div class="row panel panel-default">

    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">

        <div class="global-actions text-right">
            <a class="btn btn-success" href="<?= Url::toRoute('/cms/languages/create') ?>" role="button">
                <?= HCms::t('labels', 'Create a new language') ?>
            </a>
        </div>

        <?php if ($count) : ?>
            <?= LinkPager::widget(['pagination' => $dataProvider->getPagination(),]) ?>

            <ul class="list-group">
                <?= GridListHeader::widget(['columns' => [
                    ["width" => 9, "label" => HLib::t('labels', 'Name')],
                    ["width" => 1, "label" => HCms::t('labels', 'Code')],
                    ["width" => 2, "label" => HLib::t('labels', 'Actions'), "cssClass" => "object-actions text-right"],
                ]]) ?>

                <?php foreach ($models as $model): ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-9">
                                <?= Html::a($model->name, Url::to(['/cms/languages/view', 'id' => $model->id])) ?>
                            </div>

                            <div class="col-sm-1">
                                <?= $model->iso_639_code ?>
                            </div>

                            <div class="object-actions col-sm-2 text-right">
                                <?= GridListActionButtons::widget([
                                    'controllerRoute' => '/cms/languages',
                                    'modelId' => $model->id,
                                    'deleteMessageData' => $model->name,
                                ]) ?>
                            </div>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>

            <?= LinkPager::widget(['pagination' => $dataProvider->getPagination(),]) ?>
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

<?php
/**
 * Fiche de consultation d'un objet (backend)
 */

use app\modules\cms\HCms;
use app\modules\cms\models\WebTag;
use app\modules\hlib\widgets\ViewButtons;
use app\modules\hlib\widgets\ViewModelsList;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var WebTag       $model
 */

//die(\yii\helpers\VarDumper::dumpAsString($relatedTexts));
$this->title = HCms::t('labels', 'View tag');

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= $this->title ?></h1>
        <h2><?= Html::encode($model->label) ?></h2>
    </div>

    <div class="panel-body">

        <?= ViewButtons::widget([
            'modelId' => $model->id,
            'controllerPath' => '/cms/web-tags',
        ]) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'label',
            ],
        ]) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => HCms::t('labels', 'Language'),
                    'value' => $model->language->iso_639_code,
                ],
                [
                    'label' => HCms::t('labels', 'Base tag'),
                    'value' => ViewModelsList::widget([
                        'models' => $model->base, 'labelField' => 'code', 'controllerRoute' => '/cms/base-tags', 'listType' => 'div',
                    ]),
                    'format' => 'html',
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

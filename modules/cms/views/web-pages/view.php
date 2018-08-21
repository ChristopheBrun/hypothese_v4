<?php
/**
 * Fiche de consultation d'un objet (backend)
 */

use app\modules\cms\HCms;
use app\modules\cms\models\WebPage;
use app\modules\hlib\widgets\ViewButtons;
use app\modules\hlib\widgets\ViewModelsList;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var WebPage      $model
 * @var array        $relatedTexts [WebText]
 */

//die(\yii\helpers\VarDumper::dumpAsString($relatedTexts));
$this->title = HCms::t('labels', 'View page');

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= $this->title ?></h1>
        <h2><?= Html::encode($model->title) ?></h2>
    </div>

    <div class="panel-body">

        <?= ViewButtons::widget([
            'modelId' => $model->id,
            'controllerPath' => '/cms/web-pages',
        ]) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'title',
                'meta_description',
                'meta_keywords',
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
                    'label' => HCms::t('labels', 'Base page'),
                    'value' => ViewModelsList::widget([
                        'models' => $model->base, 'labelField' => 'code', 'controllerRoute' => '/cms/base-pages', 'listType' => 'div',
                    ]),
                    'format' => 'html',
                ],
                [
                    'label' => HCms::t('labels', 'Texts'),
                    'value' => ViewModelsList::widget([
                        'models' => $relatedTexts, 'labelField' => 'title', 'controllerRoute' => '/cms/web-texts', 'listType' => 'div',
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

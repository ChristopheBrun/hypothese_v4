<?php
/**
 * Fiche de consultation d'un objet
 */

use app\modules\cms\HCms;
use app\modules\cms\models\WebPage;
use app\modules\cms\models\WebText;
use app\modules\hlib\widgets\ViewButtons;
use app\modules\hlib\widgets\ViewModelsList;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var WebText      $model
 * @var array        $relatedPages [WebPage]
 */

$this->title = HCms::t('labels', 'View text');

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= $this->title ?></h1>
        <h2><?= Html::encode($model->title) ?></h2>
    </div>

    <div class="panel-body">

        <?= ViewButtons::widget([
            'modelId' => $model->id,
            'controllerPath' => '/cms/web-texts',
        ]) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'title',
                'subtitle',
                'description',
                'body:html',
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
                    'label' => HCms::t('labels', 'Base text'),
                    'value' => ViewModelsList::widget([
                        'models' => $model->base, 'labelField' => 'code', 'controllerRoute' => '/cms/base-texts', 'listType' => 'div',
                    ]),
                    'format' => 'html',
                ],
                [
                    'label' => HCms::t('labels', 'Pages'),
                    'value' => ViewModelsList::widget([
                        'models' => $relatedPages,
                        'labelMethod' => function (WebPage $page) {
                            return '(' . $page->base->code . ') ' . $page->title;
                        },
                        'controllerRoute' => '/cms/web-pages',
                        'listType' => 'div',
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

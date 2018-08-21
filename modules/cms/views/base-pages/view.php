<?php
/**
 * Fiche de consultation d'un objet (backend)
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BasePage;
use app\modules\cms\models\WebPage;
use app\modules\hlib\helpers\hAssets;
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\ViewModelsList;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var BasePage     $model
 */

$this->title = HCms::t('labels', 'View base page');

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= $this->title ?></h1>
        <h2><?= Html::encode($model->code) ?></h2>
    </div>

    <div class="panel-body">

        <div class="view-buttons">
            <?= Html::a(HLib::t('labels', 'Back to List'), Url::to(['/cms/web-pages/index']), ['class' => 'btn btn-primary']) ?>

            <?= Html::a(HLib::t('labels', 'Update'), Url::to(['/cms/base-pages/update', 'id' => $model->id]), ['class' => 'btn btn-success']) ?>

            <?= Html::a(HLib::t('labels', 'Delete'), Url::to(['/cms/base-pages/delete', 'id' => $model->id]), [
                'class' => 'btn btn-danger',
                'data' => ['confirm' => HLib::t('messages', 'Are you sure you want to delete this item?'), 'method' => 'delete',],
            ]) ?>
        </div>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'code',
                'route',
                'redirect_to',
                'menu_index',
            ],
        ]) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => HCms::t('labels', 'Page parent'),
                    'value' => ViewModelsList::widget([
                        'models' => $model->getParent()->one(), 'controllerRoute' => '/cms/base-pages',
                        'labelField' => 'code',
                        'listType' => 'div',
                    ]),
                    'format' => 'html',
                ],
                [
                    'label' => HCms::t('labels', 'Pages'),
                    'value' => ViewModelsList::widget([
                        'models' => $model->webPages, 'controllerRoute' => '/cms/web-pages',
                        'labelCallback' => function (WebPage $page) {
                            $lang = $page->language->iso_639_code;
                            return "[$lang]&nbsp;&nbsp;" . $page->title;
                        },
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

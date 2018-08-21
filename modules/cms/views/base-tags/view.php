<?php
/**
 * Fiche de consultation d'un objet (backend)
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BaseTag;
use app\modules\cms\models\WebTag;
use app\modules\hlib\widgets\ViewButtons;
use app\modules\hlib\widgets\ViewModelsList;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var BaseTag      $model
 */

$this->title = HCms::t('labels', 'View base tag');

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= $this->title ?></h1>
        <h2><?= Html::encode($model->code) ?></h2>
    </div>

    <div class="panel-body">

        <?= ViewButtons::widget([
            'modelId' => $model->id,
            'controllerPath' => '/cms/base-tags',
        ]) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'code',
            ],
        ]) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => HCms::t('labels', 'Web tags'),
                    'value' => ViewModelsList::widget([
                        'models' => $model->webTags, 'controllerRoute' => '/cms/web-tags',
                        'labelCallback' => function (WebTag $tag) {
                            $lang = $tag->language->iso_639_code;
                            return "[$lang]&nbsp;&nbsp;" . $tag->label;
                        },
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

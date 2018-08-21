<?php
/**
 * Fiche de consultation d'un objet
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BaseText;
use app\modules\cms\models\WebText;
use app\modules\hlib\widgets\ViewButtons;
use app\modules\hlib\widgets\ViewModelsList;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var BaseText     $model
 */

$this->title = HCms::t('labels', 'View base text');

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= $this->title ?></h1>
        <h2><?= Html::encode($model->code) ?></h2>
    </div>

    <div class="panel-body">

        <?= ViewButtons::widget([
            'modelId' => $model->id,
            'controllerPath' => '/cms/base-texts',
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
                    'label' => HCms::t('labels', 'Web texts'),
                    'value' => ViewModelsList::widget([
                        'models' => $model->webTexts, 'controllerRoute' => '/cms/web-texts',
                        'labelCallback' => function (WebText $text) {
                            $lang = $text->language->iso_639_code;
                            return "[$lang]&nbsp;&nbsp;" . $text->title;
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

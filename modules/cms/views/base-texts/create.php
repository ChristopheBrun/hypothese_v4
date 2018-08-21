<?php
/**
 * Formulaire de création
 */
use app\modules\cms\HCms;
use app\modules\cms\widgets\BaseTextForm;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model app\modules\cms\models\BaseText
 */

$this->title = HCms::t('labels', 'Create a new base text');
?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <?= BaseTextForm::widget(['model' => $model]) ?>
    </div>
</div>

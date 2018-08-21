<?php
/**
 * Formulaire de création
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BaseTag;
use app\modules\cms\widgets\BaseTagForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var BaseTag      $model
 */

$this->title = HCms::t('labels', 'Create a new base tag');

?>

<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <?= BaseTagForm::widget(['model' => $model]) ?>
    </div>
</div>



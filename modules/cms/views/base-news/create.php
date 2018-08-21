<?php
/**
 * Formulaire de création
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BaseNews;
use app\modules\cms\widgets\BaseNewsForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var BaseNews     $model
 */

$this->title = HCms::t('labels', 'Create a new base news');

?>

<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <?= BaseNewsForm::widget(['model' => $model]) ?>
    </div>
</div>



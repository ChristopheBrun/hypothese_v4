<?php
/**
 * Formulaire de création
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BasePage;
use app\modules\cms\widgets\BasePageForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var BasePage     $model
 */

$this->title = HCms::t('labels', 'Create a new base page');

?>

<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <?= BasePageForm::widget(['model' => $model]) ?>
    </div>
</div>



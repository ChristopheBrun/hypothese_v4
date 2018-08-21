<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

/**
 * Fiche de consultation d'un objet
 */

use app\modules\cms\HCms;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var $this yii\web\View
 * @var $model <?= ltrim($generator->modelClass, '\\') ?>
 */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= "<?= " ?> Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">

        <?= "<?= " ?> $this->render('@app/modules/hlib/views/partials/_viewButtons', ['routePrefix' => '/MODULE/CONTROLLER', 'modelId' => $model->id]) ?>

    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "            '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
    }
}
?>
        ],
    ]) ?>

    </div>
</div>

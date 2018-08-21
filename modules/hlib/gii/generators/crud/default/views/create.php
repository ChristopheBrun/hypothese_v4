<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

/**
 * Formulaire de création
 */

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var <?= ltrim($generator->modelClass, '\\') ?> $model
 */

$this->title = <?= $generator->generateString('Create a new ' . Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>;
$parameters = [
    'controllerRoute' => '/MODULE/CONTROLLER',
    'formAttributes' => [],
]
?>

<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= "<?= " ?> Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <?= "<?= " ?> $this->render('_form', compact('model', 'parameters')) ?>
    </div>
</div>



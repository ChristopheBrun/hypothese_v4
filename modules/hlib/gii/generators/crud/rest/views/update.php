<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

/**
 * Formulaire de mise à jour
 */
use app\modules\cms\HCms;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $model <?= ltrim($generator->modelClass, '\\') ?>
 */

$this->title = <?= $generator->generateString('Update ' . Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>;
$parameters = [
    'action' => Url::toRoute('/MODULE/CONTROLLER/post-update'),
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

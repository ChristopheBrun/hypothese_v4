<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

$leftTrimmedModelClass = ltrim($generator->modelClass, '\\');
$modelClass = Inflector::camel2id(StringHelper::basename($generator->modelClass));

echo "<?php\n";
?>

/**
* Formulaire pour la création ou la modification de l'objet
*/

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var <?= $leftTrimmedModelClass ?> $model
* @var ActiveForm $form
* @var array $parameters
*/

?>

<div class="<?= $modelClass ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin($parameters['formAttributes']); ?>

    <?php
    foreach ($generator->getColumnNames() as $attribute) :
        if (in_array($attribute, $safeAttributes)) :
            echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
        endif;
    endforeach;
    ?>

    <?= "<?= " ?> $this->render('@app/modules/hlib/views/partials/_formSubmitButtons', ['indexUrl' => Url::to([$parameters['controllerRoute'] . '/index'], true)]) ?>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>

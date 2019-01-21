<?php
/**
 * Formulaire de mise à jour
 */
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model app\models\CalendarEntry
 * @var $tags array [id => label] liste des tags disponibles
 */

$this->title = Yii::t('labels', 'Update calendar entry');
$formParameters = [];

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
            'parameters' => $formParameters,
            'tags' => $tags,
        ]) ?>
    </div>
</div>

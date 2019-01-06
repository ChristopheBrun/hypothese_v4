<?php
/**
 * Formulaire de mise à jour
 */
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $model app\models\Tag
 */

$this->title = Yii::t('labels', 'Update tag');
$this->params['breadcrumbs'][] = ['label' => Yii::t('labels', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->label, 'url' => Url::toRoute(['/tags/view', 'id' => $model->id])];
$this->params['breadcrumbs'][] = $this->title;

$formParameters = [
//    'action' => Url::toRoute(['/tags/post-update', 'id' => $model->id]),
]

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
            'parameters' => $formParameters,
        ]) ?>
    </div>
</div>

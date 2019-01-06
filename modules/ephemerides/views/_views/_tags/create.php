<?php
/**
 * Formulaire de création
 */
use app\modules\hlib\HLib;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tag */

$this->title = Yii::t('labels', 'Create a new tag');
$this->params['breadcrumbs'][] = ['label' => HLib::t('labels', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$formParameters = [
//    'action' => Url::toRoute('/tags/post-create'),
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

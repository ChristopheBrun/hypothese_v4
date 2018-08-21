<?php
/**
 * Formulaire de création
 */
use app\modules\cms\HCms;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model app\modules\cms\models\Language
 */

$this->title = HCms::t('labels', 'Create a new language');
$formParameters = [
//    'action' => Url::toRoute('/cms/languages/post-create'),
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

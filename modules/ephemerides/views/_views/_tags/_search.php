<?php
/**
 * Formulaire de recherche
 *
 * @var $this yii\web\View
 * @var $model app\models\TagSearchForm
 */

use app\modules\hlib\HLib;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/**
 * @var $form yii\widgets\ActiveForm
 */
$config = [
    'layout' => 'horizontal',
    'action' => Url::to(['/tags/search'], true),
    'id' => 'list-filter',
];

$fieldConfig = [
    'horizontalCssClasses' => [
        'label' => 'control-label col-sm-2',
    ],
];
?>

<?php $form = ActiveForm::begin($config); ?>

<div class="form-group">
    <?= Html::activeLabel($model, 'title', ['class' => 'control-label col-sm-2']) ?>
    <div class="col-sm-5">
        <div class="input-group">
            <span class="input-group-addon">
                <?= Yii::t('labels', '(contains)') ?>
            </span>
            <?= Html::activeTextInput($model, 'label', ['class' => 'form-control']) ?>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-1">
        <?= Html::submitButton(HLib::t('labels', 'Search'), ['class' => 'btn btn-primary', 'name' => 'action[submit]']) ?>
        <?= Html::submitButton(HLib::t('labels', 'Reset'), ['class' => 'btn btn-default', 'name' => 'action[clear]']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

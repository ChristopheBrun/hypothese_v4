<?php
use app\models\CalendarEntrySearchForm;
use app\modules\hlib\HLib;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Formulaire de recherche (backend)
 *
 * @var yii\web\View            $this
 * @var CalendarEntrySearchForm $model
 * @var array                   $operators
 * @var array                   $tags [id => label]
 */

$fieldConfig = [
    'horizontalCssClasses' => [
        'label' => 'control-label col-sm-2',
    ],
];

?>

<?php
/** @var $form yii\widgets\ActiveForm */
$form = ActiveForm::begin([
    'layout' => 'horizontal',
    'action' => Url::to(['/calendar-entries/register-filters'], true),
    'id' => 'list-filter',
]);
?>

<div class="form-group">
    <?= Html::activeLabel($model, 'eventDateString', ['class' => 'control-label col-sm-2']) ?>
    <div class="col-sm-5">
        <div class="input-group">
            <span class="input-group-addon">
                <?= Html::activeDropDownList($model, 'eventDateOperator', CalendarEntrySearchForm::$dateOperators) ?>
            </span>
            <?= Html::activeTextInput($model, 'eventDateString', ['class' => 'form-control']) ?>
        </div>
    </div>
</div>

<?= /** @noinspection PhpUndefinedMethodInspection */
$form->field($model, 'status', $fieldConfig)->inline()->radioList(CalendarEntrySearchForm::$statusList) ?>

<?= /** @noinspection PhpUndefinedMethodInspection */
$form->field($model, 'image', $fieldConfig)->inline()->radioList(CalendarEntrySearchForm::$imageStatusList) ?>

<?= /** @noinspection PhpUndefinedMethodInspection */
$form->field($model, 'article', $fieldConfig)->inline()->radioList(CalendarEntrySearchForm::$articleStatusList) ?>

<div class="form-group">
    <?= Html::activeLabel($model, 'title', ['class' => 'control-label col-sm-2']) ?>
    <div class="col-sm-5">
        <div class="input-group">
            <span class="input-group-addon">
                <?= Yii::t('labels', '(contains word)') ?>
            </span>
            <?= Html::activeTextInput($model, 'title', ['class' => 'form-control']) ?>
        </div>
    </div>
</div>

<div class="form-group">
    <?= Html::activeLabel($model, 'body', ['class' => 'control-label col-sm-2']) ?>
    <div class="col-sm-5">
        <div class="input-group">
            <span class="input-group-addon">
                <?= Yii::t('labels', '(contains word)') ?>
            </span>
            <?= Html::activeTextInput($model, 'body', ['class' => 'form-control']) ?>
        </div>
    </div>
</div>

<div class="form-group">
    <?= Html::activeLabel($model, 'tag', ['class' => 'control-label col-sm-2']) ?>
    <div class="col-sm-5">
        <div class="input-group">
            <span class="input-group-addon">
                <?= Yii::t('labels', '(owns)') ?>
            </span>
            <?= Html::activeDropDownList($model, 'tag', $tags, ['class' => 'form-control', 'prompt' => '']) ?>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-1">
        <?= Html::submitButton(HLib::t('labels', 'Search'), ['class' => 'btn btn-primary', 'name' => 'action[submit]']) ?>
        <?= Html::submitButton(HLib::t('labels', 'Reset'), ['class' => 'btn btn-default', 'name' => 'action[clear]']) ?>
    </div>
</div>

<?php
ActiveForm::end();
?>

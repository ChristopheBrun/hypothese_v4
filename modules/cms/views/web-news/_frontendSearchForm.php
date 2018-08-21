<?php
use app\modules\cms\models\forms\WebNewsSearch;
use app\modules\hlib\HLib;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Formulaire de recherche (frontend)
 *
 * @var yii\web\View  $this
 * @var WebNewsSearch $model
 * @var array         $operators
 * @var array         $tags [id => label]
 */

?>
<?php
/** @var $form yii\widgets\ActiveForm */
$form = ActiveForm::begin([
    'layout' => 'default',
    'action' => Url::to(['/cms/web-news/post-search'], true),
    'options' => ['class' => 'frontend-search-form'],
]);
?>
<div class="row">
    <div class="col-sm-3">
        <?= Html::activeLabel($model, 'title', ['class' => 'control-label']) ?>
        <?= Html::activeTextInput($model, 'title', ['class' => 'form-control']) ?>
    </div>

    <div class="col-sm-3">
        <?= Html::activeLabel($model, 'body', ['class' => 'control-label']) ?>
        <?= Html::activeTextInput($model, 'body', ['class' => 'form-control']) ?>
    </div>

    <div class="col-sm-3">
        <?= Html::activeLabel($model, 'tagId', ['class' => 'control-label']) ?>
        <?= Html::activeDropDownList($model, 'tagId', $tags, ['class' => 'form-control', 'prompt' => '']) ?>
    </div>

    <div class="col-sm-3">
        <?= Html::submitButton(HLib::t('labels', 'All'), ['class' => 'btn btn-default search-button', 'name' => 'action[clear]']) ?>
        <?= Html::submitButton(HLib::t('labels', 'Search'), ['class' => 'btn btn-primary search-button', 'name' => 'action[submit]']) ?>
    </div>
</div>
<?php
ActiveForm::end();
?>

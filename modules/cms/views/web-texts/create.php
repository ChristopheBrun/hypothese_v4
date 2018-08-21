<?php
/**
 * Formulaire de création
 */
use app\modules\cms\HCms;
use app\modules\cms\models\BaseText;
use app\modules\cms\models\WebText;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var WebText      $model
 * @var BaseText     $baseModel
 * @var array        $baseTexts [BaseText]
 * @var array        $languages [Language]
 */

$this->title = HCms::t('labels', 'Create a new text');
$parameters = [
//    'action' => Url::toRoute('/cms/web-texts/post-create'),
];

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <?= $this->render('_form', compact('model', 'parameters', 'baseTexts', 'languages', 'baseModel')) ?>
    </div>
</div>

<?php
/**
 * Formulaire de mise à jour
 */
use app\modules\cms\HCms;
use yii\helpers\Html;
use app\modules\cms\models\WebText;
use app\modules\cms\models\BaseText;

/**
 * @var yii\web\View $this
 * @var WebText      $model
 * @var BaseText     $baseModel
 * @var array        $baseTexts [BaseText]
 * @var array        $languages [Language]
 */

$this->title = HCms::t('labels', 'Update a text');
$parameters = [
//    'action' => Url::toRoute(['/cms/web-texts/post-update', 'id' => $model->id]),
]

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <?= $this->render('_form', compact('model', 'parameters', 'baseTexts', 'languages', 'baseModel')) ?>
    </div>
</div>

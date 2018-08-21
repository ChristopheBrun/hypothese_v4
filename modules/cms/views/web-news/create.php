<?php
/**
 * Formulaire de création
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BaseNews;
use app\modules\cms\models\WebNews;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var WebNews      $model
 * @var BaseNews     $baseModel
 * @var array        $basePages [BaseNews]
 * @var array        $languages [Language]
 */

$this->title = HCms::t('labels', 'Create a new web news');

?>

<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <?= $this->render('_form', compact('model', 'baseNews', 'languages', 'baseModel')) ?>
    </div>
</div>



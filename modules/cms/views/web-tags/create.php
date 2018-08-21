<?php

/**
 * Formulaire de création
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BaseTag;
use app\modules\cms\models\WebTag;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var WebTag       $model
 * @var BaseTag     $baseModel
 * @var array        $baseTags [BaseTag]
 * @var array        $languages [Language]
 */

$this->title = HCms::t('labels', 'Create a new tag');

?>

<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <?= $this->render('_form', compact('model', 'baseTags', 'languages', 'baseModel')) ?>
    </div>
</div>



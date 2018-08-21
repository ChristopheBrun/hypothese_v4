<?php

/**
 * Formulaire de création
 */
use app\modules\cms\HCms;
use app\modules\cms\models\BasePage;
use app\modules\cms\models\WebPage;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var WebPage      $model
 * @var BasePage     $baseModel
 * @var array        $basePages [BasePage]
 * @var array        $languages [Language]
 */

$this->title = HCms::t('labels', 'Create a new page');

?>

<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <?= $this->render('_form', compact('model', 'basePages', 'languages', 'baseModel')) ?>
    </div>
</div>



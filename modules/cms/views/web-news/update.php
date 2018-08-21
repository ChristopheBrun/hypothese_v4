<?php
/**
 * Formulaire de mise à jour
 */

use app\modules\cms\HCms;
use app\modules\cms\models\BaseNews;
use app\modules\cms\models\WebNews;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var WebNews      $model
 * @var BaseNews     $baseModel
 * @var array        $baseNews [BaseNews]
 * @var array        $languages [Language]
 */

$this->title = HCms::t('labels', 'Update news : {label}', ['label' => $model->base->event_date . ' - ' . $model->language->iso_639_code]);

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <?= $this->render('_form', compact('model', 'baseNews', 'languages', 'baseModel')) ?>
    </div>
</div>

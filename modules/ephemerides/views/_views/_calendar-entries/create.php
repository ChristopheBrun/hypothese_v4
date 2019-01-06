<?php
/**
 * Formulaire de création
 */

use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model app\models\CalendarEntry
 * @var $tags array  [id => label]
 * @var $articles array  [id => title]
 */

$this->title = Yii::t('labels', 'Create a new calendar entry');
$formParameters = [
//    'action' => Url::toRoute('/calendar-entries/post-create'),
];

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
            'parameters' => $formParameters,
            'tags' => $tags,
            'articles' => $articles,
        ]) ?>
    </div>
</div>

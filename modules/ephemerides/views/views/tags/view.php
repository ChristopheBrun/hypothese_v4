<?php
/**
 * Fiche de consultation d'un objet (backend)
 */
use app\modules\hlib\widgets\ViewButtons;
use app\modules\hlib\widgets\ViewModelsList;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tag */

$this->title = Yii::t('labels', 'View tag') . ' : ' . $model->label;
$this->params['breadcrumbs'][] = ['label' => Yii::t('labels', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->label, 'url' => Url::toRoute(['/tags/view', 'id' => $model->id])];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="panel-body">

        <?= ViewButtons::widget([
            'modelId' => $model->id,
            'controllerPath' => '/tags',
        ]) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'label',
                [
                    'label' => Yii::t('labels', 'Calendar entries'),
                    'value' => ViewModelsList::widget([
                        'models' => $model->calendarEntries, 'labelField' => 'title', 'controllerRoute' => '/calendar-entries'
                    ]),
                    'format' => 'html',
                ],
            ],
        ]) ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'created_at',
                'updated_at',
            ],
        ]) ?>

    </div>
</div>

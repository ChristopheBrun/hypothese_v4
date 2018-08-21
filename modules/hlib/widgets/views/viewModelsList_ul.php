<?php
use app\modules\hlib\HLib;
use app\modules\hlib\widgets\ViewModelsList;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var ViewModelsList $widget */
$widget = $this->context;

/** @var $model ActiveRecord */
?>

<ul>
    <?php foreach ($widget->models as $model) : ?>
        <li>
            <?php
            /** @noinspection PhpUndefinedFieldInspection */
            echo Html::a($widget->retrieveLabel($model), Url::to([$widget->controllerRoute . '/view', 'id' => $model->id]));

            /** @noinspection PhpUndefinedFieldInspection */
            if ($widget->checkEnabled && !$model->enabled) {
                echo "&nbsp;<i>(" . mb_strtolower(HLib::t('labels', 'Disabled')) . ")</i>";
            }
            ?>
        </li>
    <?php endforeach ?>
</ul>

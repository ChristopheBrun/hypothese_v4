<?php
use app\modules\hlib\widgets\DisplayModelsWithLinks;
use yii\db\ActiveRecord;
use yii\web\View;

/**
 * @var $model ActiveRecord
 * @var DisplayModelsWithLinks $this->context
 * @var View $this
 */

if (!$this->context->models) {
    return "";
}

?>

<ul>
    <?php foreach ($this->context->models as $model) : ?>
        <?php $url = $this->context->getViewUrl($model); ?>
        <li>
            <?php if(!$this->context->checkEnabled) : ?>
                <?= $url ?>
            <?php /** @noinspection PhpUndefinedFieldInspection */
            elseif($this->context->checkEnabled && $model->enabled) : ?>
                <i class="fa fa-check-square-o"></i>
                <?= $url ?>
            <?php else : ?>
                <i class="fa fa-close"></i>
                <?= $url ?>
            <?php endif ?>
        </li>
    <?php endforeach ?>
</ul>

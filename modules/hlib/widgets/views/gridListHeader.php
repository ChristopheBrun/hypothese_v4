<?php

/**
 * @var array $columns
 * @var array $column
 */
use app\modules\hlib\widgets\GridListHeader;

/** @var GridListHeader $context */
$context = $this->context;
?>
<li class="list-group-item grid-list-header">
    <div class="row">
        <?php foreach($columns as $column) : ?>
            <div class="col-sm-<?= $column['width'] ?> <?= $column['cssClass'] ?>">
                <?= /** @noinspection PhpUnhandledExceptionInspection */
                $context->renderColumnContent($column) ?>
            </div>
        <?php endforeach ?>
    </div>
</li>

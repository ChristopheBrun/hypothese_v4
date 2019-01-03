<?php
use app\modules\cms\models\WebText;
use app\modules\cms\models\WebPage;

/**
 * @var         $this yii\web\View
 * @var WebPage $page
 */

$this->title = $page->title;
$this->registerMetaTag(['description' => $page->meta_description]);
/** @var WebText $text */
$text = $page->getText('Présentation', new WebText(['title' => '??', 'body' => '??']));

?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1><?= $text->title ?></h1>
        </div>

        <div class="panel-body">
            <?= $text->body; ?>
        </div>
    </div>
</div>
<?php
/**
 * Affiche la liste des entrées de menu associées à des pages web
 */

use app\modules\cms\models\WebPage;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var array $pages [WebPages]
 *
 * @var $page WebPage
 */

?>
<ul class="nav navbar-nav" role="menu">
    <?php foreach ($pages as $page) : ?>
        <li><?= Html::a($page->menu_title, Url::to([$page->base->route], true)) ?></li>
    <?php endforeach ?>
</ul>

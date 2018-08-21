<?php
use app\modules\cms\HCms;
use yii\bootstrap\Html;
use yii\helpers\Url;

/**
 *
 */

?>
<ul class="dropdown-menu" role="menu">
    <li><?= Html::a(HCms::t('labels', 'Pages'), Url::to(['/cms/web-pages/index'], true)) ?></li>
    <li><?= Html::a(HCms::t('labels', 'Web texts'), Url::to(['/cms/web-texts/index'], true)) ?></li>
    <li><?= Html::a(HCms::t('labels', 'Web news'), Url::to(['/cms/web-news/index'], true)) ?></li>
    <li class="menu-separator"> </li>
    <li><?= Html::a(HCms::t('labels', 'Web tags'), Url::to(['/cms/web-tags/index'], true)) ?></li>
    <li><?= Html::a(HCms::t('labels', 'Languages'), Url::to(['/cms/languages/index'], true)) ?></li>
</ul>


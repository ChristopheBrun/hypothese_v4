<?php

use app\modules\cms\models\WebTag;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Affiche une liste de boutons pour les tags du modèle
 *
 * @var WebTag[] $tags
 * @var string   $buttonSizeClass
 *
 * @var WebTag   $tag
 */

?>

<div>
    <?php foreach ($tags as $tag) : ?>
        <?= Html::a(
            Html::encode($tag->label),
            Url::to(['/cms/web-news/post-search', 'tagId' => $tag->id]), ['class' => 'btn btn-primary ' . $buttonSizeClass]
        ) ?>
    <?php endforeach ?>
</div>

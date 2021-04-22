<?php

/**
 * Présentation standard
 *
 * @var $this \yii\web\View
 * @var $content string
 */

use app\modules\hlib\HLib;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?= $this->render('//site/_mainMenu') ?>
    <div class="container">
        <?= /** @noinspection PhpUnhandledExceptionInspection */
        Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <?= /** @noinspection PhpUnhandledExceptionInspection */
        Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Hypothese.net &copy; 2013 - <?= date('Y'); ?></p>

        <p class="pull-right"><?= HLib::t('labels', 'Version') . ' ' . Yii::$app->version ?></p>
        <p class="pull-right"><?= YII_ENV != 'prod' ? 'YII_ENV = ' . YII_ENV : '' ?>&nbsp;&nbsp;&nbsp;&nbsp;</p>
    </div>
</footer>

<?php $this->registerMetaTag(['name' => 'keywords', 'content' => implode(',', Yii::$app->getKeywordsMetaTags())]) ?>

<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>

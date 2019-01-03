<?php
/**
 * Layout principal du site
 */

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;

/**
 * @var $this \yii\web\View
 * @var $content string
 */

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--suppress JSUnresolvedLibraryURL -->
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?= Html::csrfMetaTags() ?>

    <title><?= Html::encode($this->title) ?></title>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="shortcut icon" type="image/x-icon" href="<?= Url::to('/favicon.ico', true) ?>">

    <?php $this->head(); ?>

    <?php
    /** @noinspection PhpUndefinedFieldInspection */
    if (isset($this->context->enableGoogleAnalytics) && $this->context->enableGoogleAnalytics === true) {
        echo $this->render('//partials/_googleAnalytics');
    }
    ?>
</head>

<body>
<?php $this->beginBody() ?>

    <?= $content ?>

<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>

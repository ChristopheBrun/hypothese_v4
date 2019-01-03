<?php
/**
 *
 */
$this->beginContent('@app/views/layouts/main.php');
?>

    <div class="container backend">
        <?= $this->render('//partials/_mainMenu.php') ?>

        <?= $this->render('@app/modules/hlib/views/partials/_flashes.php') ?>

        <?= $content ?>

        <?= $this->render('//partials/_footer.php') ?>

    </div>

<?php $this->endContent(); ?>
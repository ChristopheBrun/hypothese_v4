<?php
/**
 * Aides pour les regex
 */

use app\lib\enums\PregmatchType;
use yii\bootstrap\Html;

/** @var string $regex */
/** @var string $string */
/** @var string $pregmatch @see enum PregmatchType */
/** @var bool|int $result */
/** @var array $matches */

$this->title = Yii::$app->name;

?>

<div class="panel panel-default" id="homepage">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12">
                <h1>Regex PHP</h1>
            </div>
        </div>
    </div>

    <div class="panel-body">

        <?= Html::beginForm() ?>
        <div class="row">
            <div class="col-sm-12 form-group">
                <label for="regex">Expression régulière à utiliser</label>
                <div class="col-sm-12 hint-block">
                    Exemple : /^\d+[\d\w]+$/
                </div>
                <?= Html::textInput('regex', $regex, ['class' => "form-control", 'id' => 'regex']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 form-group">
                <label for="regex">Chaine de caractères à analyser avec la regex</label>
                <?= Html::textInput('string', $string, ['class' => "form-control", 'id' => 'string']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 form-group">
                <label for="regex">Analyser avec...</label>
                <?= Html::radioList('pregmatch', $pregmatch, PregmatchType::getList(), ['id' => 'pregmatch']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 form-group">
                <button class="btn btn-success" id="path-windows-submit">Envoyer</button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 explications">
                <ul>
                    <li>Prend une regex et une chaîne à analyser en entrée</li>
                    <li>Renvoie les résultats du preg_match() ou du preg_match_all()</li>
                    <li>Le traitement se fait en PHP côté serveur</li>
                </ul>
            </div>
        </div>

        <?php if ($pregmatch == PregmatchType::SIMPLE): ?>
            <?= $this->render('_regexSimple', compact('result', 'matches')) ?>
        <?php else : ?>
            <?= $this->render('_regexAll', compact('result', 'matches')) ?>
        <?php endif ?>

        <?= Html::endForm() ?>
    </div>

    <?= $this->render('/site/_inner-footer') ?>
</div>


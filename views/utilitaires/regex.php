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
/** @var array $parentheses */

$this->title = "Expressions régulières";
$this->params['breadcrumbs'][] = ['label' => 'Utilitaires', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
Yii::$app->addKeywordsMetaTags(["PHP", "Regex", "Expressions régulières", "Expression régulière"]);

?>

<div class="panel panel-default" id="homepage">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12">
                <h1>Testez vos expressions régulières PHP</h1>
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
                    <li>NB : si vous avez besoin d'un outil vraiment complet et puissant, essayez
                        <?= Html::a("Regex101.com", 'https://regex101.com/', ['target' => 'blank']) ?>
                    </li>
                </ul>
            </div>
        </div>

        <?php if ($pregmatch == PregmatchType::SIMPLE): ?>
            <?= $this->render('_regexSimple', compact('result', 'matches', 'parentheses')) ?>
        <?php else : ?>
            <?= $this->render('_regexAll', compact('result', 'matches', 'parentheses')) ?>
        <?php endif ?>

        <?= Html::endForm() ?>
    </div>

    <?= $this->render('/site/_inner-footer') ?>
</div>


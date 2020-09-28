<?php
/**
 * Aides pour les regex
 */

use yii\bootstrap\Html;
use yii\widgets\DetailView;

/** @var string $regex */
/** @var string $string */
/** @var bool|int $result */
/** @var array $matches */

$this->title = Yii::$app->name;
// @see https://highlightjs.org/download/
$this->registerJsFile('@web/js/highlight/highlight.pack.js');
$this->registerCssFile('@web/js/highlight/styles/default.css');

if (!is_null($result)) {
    $detailViewAttributes = [[
        'label' => "Résultat",
        'value' => $result === false ? '<div style="font-weight: bold;color: red">false</div>' : $result,
        'format' => 'html',
    ]];

    if ($result) {
        $detailViewAttributes[] = [
            'label' => "Chaine satisfaisant la regex",
            'value' => $matches[0],
        ];
    }
    for ($i = 1; $i < count($matches); ++$i) {
        $detailViewAttributes[] = [
            'label' => "Parenthèse capturante n°$i",
            'value' => $matches[$i],
        ];
    }
}

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
                <label for="regex">Copier la regex ici</label>
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
                <button class="btn btn-success" id="path-windows-submit">Envoyer</button>
            </div>
        </div>

        <?php if (!is_null($result)): ?>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <?= DetailView::widget([
                        'model' => 111,
                        'attributes' => $detailViewAttributes,
                    ]) ?>
                </div>
            </div>
        <?php endif ?>
        <?= Html::endForm() ?>

    </div>

    <div class="row">
        <div class="col-sm-12 explications">
            <ul>
                <li>Prend une regex et une chaîne à analyser en entrée</li>
                <li>Renvoie mesrésultats du preg_match().
                </li>
                <li>Le traitement se fait en PHP côté serveur :</li>
            </ul>
            <pre>
                    <code class="php">
 $result = preg_match($regex, $string, $matches);
                    </code>
                 </pre>
        </div>
    </div>
</div>

<?= $this->render('/site/_inner-footer') ?>

</div>


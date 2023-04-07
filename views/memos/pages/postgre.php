<?php

use yii\helpers\Html;
use yii\web\View;

$this->title = "Postgre";
$this->params['breadcrumbs'][] = ['label'=> 'Mémos', 'url' => ['git']];
$this->params['breadcrumbs'][] = $this->title;
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
Yii::$app->addKeywordsMetaTags(['PostgreSQL', 'Postgre']);

// @see https://highlightjs.org/download/
$this->registerCssFile('@web/js/highlight/styles/agate.css');
$this->registerJsFile('@web/js/highlight/highlight.pack.js');
$this->registerJs(<<<JS
    // noinspection JSUnresolvedFunction,JSUnresolvedVariable
    hljs.initHighlightingOnLoad();
JS, View::POS_END);

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-12">
                <h1><?= $this->title ?></h1>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <h2>Importer une base de données Postgre en ligne de commande sous Windows</h2>
                <ul>
                    <li>
                        La base de données doit exister dans Postgre. Si ce n'est pas le cas, creez la.
                    </li>
                    <li>
                        Positionnez-vous si nécessaire dans le répertoire de l'exécutable
                        (quelque chose du genre "C:/Program Files/PostgreSQL/15/bin")
                        <br/>
                        <pre>
                            <code>
                            # Connexion au serveur
                            ./psql -U monuser
                            # Saisir le mot de passe qui vous est demandé
                            # Vous devriez maintenant avoir une invite du type 'postgres-#'

                            # Connexion à la base de données
                            \c mabase

                            # Importer le script en indiquant le path correct
                            \set ON_ERROR_STOP on
                            \i 'C:/dumps/import.sql'
                            </code>
                         </pre>
                        <?= Html::a(
                            'https://www.microfocus.com/documentation/idol/IDOL_12_0/MediaServer/Guides/html/English/Content/Getting_Started/Configure/_TRN_Set_up_PostgreSQL.htm',
                            'https://www.microfocus.com/documentation/idol/IDOL_12_0/MediaServer/Guides/html/English/Content/Getting_Started/Configure/_TRN_Set_up_PostgreSQL.htm',
                            ['target' => 'blank']) ?>

                    </li>
                </ul>
            </div>
        </div>
    </div>

    <?= $this->render('/site/_inner-footer', ['dateMaj' => '06/04/2023']) ?>

</div>


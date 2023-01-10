<?php

use yii\helpers\Html;
use yii\web\View;

$this->title = "Commandes Git";
$this->params['breadcrumbs'][] = ['label'=> 'Mémos', 'url' => ['git']];
$this->params['breadcrumbs'][] = $this->title;
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
Yii::$app->addKeywordsMetaTags(['Git']);

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
                <h2>Gestion des branches</h2>
                <ul>
                    <li>
                        Une bonne explication de l'utilisation du Power Shell est disponible sur cette page :
                        <br/>
                        <?= Html::a(
                            'https://www.tutorialspoint.com/how-to-get-environment-variable-value-using-powershell',
                            'https://www.tutorialspoint.com/how-to-get-environment-variable-value-using-powershell',
                            ['target' => 'blank']) ?>
                        <pre>
                            <code>
                            # .NET
                            # si une ligne est trop longue, la colonne "Value" sera tronquée ici...
                            [System.Environment]::GetEnvironmentVariables()
                            # ... mais pas ici car le résultat est affiché sur une seule colonne
                            [System.Environment]::GetEnvironmentVariable('PATH')

                            # ls
                            # si une ligne est trop longue, la colonne "Value" sera tronquée
                            ls Env:* # ls Env:
                            ls Env:PATH

                            # dir
                            # si une ligne est trop longue, la colonne "Value" sera tronquée
                            dir env:* # dir env:
                            dir env:PATH

                            # Power Shell
                            # si une ligne est trop longue, la colonne "Value" sera tronquée
                            Get-ChildItem -Path Env:* # Get-ChildItem -Path Env:
                            Get-ChildItem -Path Env:PATH
                            gci env:PATH
                            # avec tri des lignes
                            gci env:* | sort-object name
                            # avec un filtre sur le nom des variables recherchées
                            gci env:* | where name -like 'P*'
                            # pour avoir un résultat lisible et non tronqué, on peut appliquer un séparateur au résultat
                            # si on l'envoie dans un fichier, il sera enregistré par défaut dans votre répertoire utilisateur
                            $env:PATH -split ';' > path.txt
                            </code>
                         </pre>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <?= $this->render('/site/_inner-footer', ['dateMaj' => '05/10/2022']) ?>

</div>


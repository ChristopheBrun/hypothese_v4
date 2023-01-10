<?php

use yii\helpers\Html;
use yii\web\View;

$this->title = "Configuration PHP";
$this->params['breadcrumbs'][] = ['label'=> 'Mémos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
Yii::$app->addKeywordsMetaTags(['Configuration', 'PHP', 'PEAR', 'WAMP']);

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
                <h2>Installation PEAR</h2>
                <ul>
                    <li>
                        Pour installer facilement des extensions PECL, il faut avoir <?= Html::a(
                            'une version de PEAR à jour',
                            'https://pear.php.net/manual/en/installation.getting.php',
                            ['target' => 'blank']) ?> sur sa machine.
                    </li>
                    <li>
                        <div class="memo-question">
                            Quand je fais tourner le script d'installation de PEAR, il me demande
                            en boucle de valider la configuration
                        </div>
                        Lorsque vous faites executez <i>go-pear.phar</i>, n'oubliez pas de renseigner le chemin
                        vers le dossier du <i>php.exe</i> que vous voulez utiliser. Cette ligne de configuration n'est
                        pas renseignée par défaut, si elle est vide l'installation bloque (cf l'option 13 sur l'exemple
                        ci-dessous)
                        <pre>
                            <code>
                             1. Installation base ($prefix)                   : D:\Dev\Web\wamp\bin\php\php7.4.13
                             2. Temporary directory for processing            : D:\Dev\Web\wamp\bin\php\php7.4.13\tmp
                             3. Temporary directory for downloads             : D:\Dev\Web\wamp\bin\php\php7.4.13\tmp
                             4. Binaries directory                            : D:\Dev\Web\wamp\bin\php\php7.4.13
                             5. PHP code directory ($php_dir)                 : D:\Dev\Web\wamp\bin\php\php7.4.13\pear
                             6. Documentation directory                       : D:\Dev\Web\wamp\bin\php\php7.4.13\docs
                             7. Data directory                                : D:\Dev\Web\wamp\bin\php\php7.4.13\data
                             8. User-modifiable configuration files directory : D:\Dev\Web\wamp\bin\php\php7.4.13\cfg
                             9. Public Web Files directory                    : D:\Dev\Web\wamp\bin\php\php7.4.13\www
                            10. System manual pages directory                 : D:\Dev\Web\wamp\bin\php\php7.4.13\man
                            11. Tests directory                               : D:\Dev\Web\wamp\bin\php\php7.4.13\tests
                            12. Name of configuration file                    : D:\Dev\Web\wamp\bin\php\php7.4.13\pear.ini
                            13. Path to CLI php.exe                           :

                            1-13, 'all' or Enter to continue:
                            </code>
                        </pre>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <h2>Mise à jour avec WAMP</h2>
                <ul>
                    <li>
                        <?= Html::a(
                            "Ressources nécessaires à la mise à jour d'une installation WAMP",
                            'https://wampserver.aviatechno.net',
                            ['target' => 'blank']) ?>
                    </li>
                    <li>
                        Après la mise à jour de WAMP et des versions Apache, PHP etc., il faudra peut-être configurer
                        quelques éléments supplémentaires pour avoir un serveur local exploitable :
                        <ul>
                            <li>
                                Indiquer à WAMP quelle est la version du CLI à utiliser :
                                <br/><i>&rarr; icône Wamp Server > clic droit > Outils > Changer la version PHP CLI </i>
                            </li>
                            <li>
                                Mettre à jour le PATH Windows vers la bonne version de PHP CLI.
                                <br/>&rarr; <?= Html::a(
                                        "console Windows & variables d'environnement",
                                        ['console-windows']) ?>
                            </li>
                            <li>
                                Ajouter les lignes de configuration pour XDebug dans le bon fichier <i>php.ini</i>
                                <pre>
                                    <code>
                                    ; XDEBUG Extension
                                    [xdebug]
                                    zend_extension="D:/Dev/Web/wamp/bin/php/php7.4.13/zend_ext/php_xdebug-2.9.8-7.4-vc15-x86_64.dll"

                                    xdebug.remote_enable = On
                                    xdebug.profiler_enable = On
                                    xdebug.profiler_enable_trigger = On
                                    xdebug.profiler_output_name = cachegrind.out.%t.%p
                                    xdebug.profiler_output_dir ="D:/Dev/Web/wamp/tmp"
                                    xdebug.show_local_vars=0
                                    </code>
                                </pre>
                            </li>
                            <li>... et bien entendu relancer les services</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <?= $this->render('/site/_inner-footer', ['dateMaj' => '03/01/2021']) ?>

</div>


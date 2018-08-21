<?php
use yii\helpers\Url;

/* @var $scenario Codeception\Scenario */
$I = new AcceptanceTester($scenario);
$I->wantTo('vérifier que la page cms/base-pages/index fonctionne bien');

$pageUrl = Url::to(['/cms/base-pages/index']);
$wait = 1; // secondes
$I->checkAccessFiltersAndLog($pageUrl, 'c.brun@hypothese.net', 'ew0oVQQkaCvCGwmIxK7a', 'Liste des pages/parent');

$I->expect('le lien de création fonctionne');
$I->seeLink('Ajouter une page/racine');
$I->click('Ajouter une page/racine');
$I->see('Ajouter une page/racine', 'h1');

$I->expect('le lien de mise à jour fonctionne');
$I->amOnPage($pageUrl);
$url = Url::to(['/cms/base-pages/update', 'id' => 1], true);
$I->seeElement(['css' => 'a[href="' . $url . '"]']);
$I->click(['css' => 'a[href="' . $url . '"]']);
$I->wait($wait);
$I->see('Modifier une page/racine', 'h1');

$I->expect('le lien vers la fiche fonctionne');
$I->amOnPage($pageUrl);
$url = Url::to(['/cms/base-pages/view', 'id' => 1], true);
$I->seeElement(['css' => 'a[href="' . $url . '"]']);
$I->click(['css' => 'a[href="' . $url . '"]']);
$I->wait($wait);
$I->see('Fiche d\'une page/racine', 'h1');

$I->expect('la suppression fonctionne');
$I->amOnPage($pageUrl);
$url = Url::to(['/cms/base-pages/delete', 'id' => 1], true);
$I->seeElement(['css' => 'a[href="' . $url . '"]']);
$I->click(['css' => 'a[href="' . $url . '"]']);
$I->acceptPopup();
$I->wait($wait);
$I->see('Suppression réussie');
$I->dontSeeElement(['css' => 'a[href="' . $url . '"]']);


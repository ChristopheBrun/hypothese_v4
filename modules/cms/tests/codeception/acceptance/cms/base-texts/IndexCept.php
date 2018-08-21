<?php
use yii\helpers\Url;

/* @var $scenario Codeception\Scenario */
$I = new AcceptanceTester($scenario);
$I->wantTo('vérifier que la page cms/base-texts/index fonctionne bien');

$pageUrl = Url::to(['/cms/base-texts/index']);
$wait = 1; // secondes
$I->checkAccessFiltersAndLog($pageUrl, 'c.brun@hypothese.net', 'ew0oVQQkaCvCGwmIxK7a', 'Liste des textes/parent');

$I->expect('le lien de création fonctionne');
$I->seeLink('Ajouter un texte/racine');
$I->click('Ajouter un texte/racine');
$I->see('Ajouter un texte/racine', 'h1');

$I->expect('le lien de mise à jour fonctionne');
$I->amOnPage(Url::to(['/cms/base-texts/index']));
$url = Url::to(['/cms/base-texts/update', 'id' => 1], true);
$I->seeElement(['css' => 'a[href="' . $url . '"]']);
$I->click(['css' => 'a[href="' . $url . '"]']);
$I->wait($wait);
$I->see('Modifier un texte/racine', 'h1');

$I->expect('le lien vers la fiche fonctionne');
$I->amOnPage(Url::to(['/cms/base-texts/index']));
$url = Url::to(['/cms/base-texts/view', 'id' => 1], true);
$I->seeElement(['css' => 'a[href="' . $url . '"]']);
$I->click(['css' => 'a[href="' . $url . '"]']);
$I->wait($wait);
$I->see('Fiche d\'un texte/racine', 'h1');

$I->expect('la suppression fonctionne');
$I->amOnPage(Url::to(['/cms/base-texts/index']));
$url = Url::to(['/cms/base-texts/delete', 'id' => 1], true);
$I->seeElement(['css' => 'a[href="' . $url . '"]']);
$I->click(['css' => 'a[href="' . $url . '"]']);
$I->acceptPopup();
$I->wait($wait);
$I->see('Suppression réussie');
$I->dontSeeElement(['css' => 'a[href="' . $url . '"]']);


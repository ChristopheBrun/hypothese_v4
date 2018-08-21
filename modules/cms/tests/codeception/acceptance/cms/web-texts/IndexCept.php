<?php
use yii\helpers\Url;

/* @var $scenario Codeception\Scenario */
$I = new AcceptanceTester($scenario);
$I->wantTo('vérifier que la page cms/web-texts/index fonctionne bien');

$pageUrl = Url::to(['/cms/web-texts/index']);
$wait = 1; // secondes
$I->checkAccessFiltersAndLog($pageUrl, 'c.brun@hypothese.net', 'ew0oVQQkaCvCGwmIxK7a', 'Liste des textes');

$I->expect('le lien de création fonctionne');
$I->seeLink('Ajouter un texte');
$I->click('Ajouter un texte');
$I->see('Ajouter un texte', 'h1');

$I->expect('le lien de mise à jour fonctionne');
$I->amOnPage(Url::to(['/cms/web-texts/index']));
$url = Url::to(['/cms/web-texts/update', 'id' => 2], true);
$I->seeElement(['css' => 'a[href="' . $url . '"]']);
$I->click(['css' => 'a[href="' . $url . '"]']);
$I->wait($wait);
$I->see('Modifier un texte', 'h1');

$I->expect('le lien vers la fiche fonctionne');
$I->amOnPage(Url::to(['/cms/web-texts/index']));
$url = Url::to(['/cms/web-texts/view', 'id' => 2], true);
$I->seeElement(['css' => 'a[href="' . $url . '"]']);
$I->click(['css' => 'a[href="' . $url . '"]']);
$I->wait($wait);
$I->see('Fiche d\'un texte', 'h1');

$I->expect('la suppression fonctionne');
$I->amOnPage(Url::to(['/cms/web-texts/index']));
$url = Url::to(['/cms/web-texts/delete', 'id' => 2], true);
$I->seeElement(['css' => 'a[href="' . $url . '"]']);
$I->click(['css' => 'a[href="' . $url . '"]']);
$I->acceptPopup();
$I->wait($wait);
$I->see('Suppression réussie');
$I->dontSeeElement(['css' => 'a[href="' . $url . '"]']);


<?php
use yii\helpers\Url;

/* @var $scenario Codeception\Scenario */
$I = new AcceptanceTester($scenario);
$I->wantTo('vérifier que la page cms/web-pages/index fonctionne bien');

$pageUrl = Url::to(['/cms/web-pages/index']);
$wait = 1; // secondes
$I->checkAccessFiltersAndLog($pageUrl, 'c.brun@hypothese.net', 'ew0oVQQkaCvCGwmIxK7a', 'Liste des pages');

$I->expect('le lien de création fonctionne');
$I->seeLink('Ajouter une page');
$I->click('Ajouter une page');
$I->see('Ajouter une page', 'h1');

$I->expect('le lien de mise à jour fonctionne');
$I->amOnPage(Url::to(['/cms/web-pages/index']));
$url = Url::to(['/cms/web-pages/update', 'id' => 1], true);
$I->seeElement(['css' => 'a[href="' . $url . '"]']);
$I->click(['css' => 'a[href="' . $url . '"]']);
$I->wait($wait);
$I->see('Modifier une page', 'h1');

$I->expect('le lien vers la fiche fonctionne');
$I->amOnPage(Url::to(['/cms/web-pages/index']));
$url = Url::to(['/cms/web-pages/view', 'id' => 1], true);
$I->seeElement(['css' => 'a[href="' . $url . '"]']);
$I->click(['css' => 'a[href="' . $url . '"]']);
$I->wait($wait);
$I->see('Fiche d\'une page', 'h1');

$I->expect('la suppression fonctionne');
$I->amOnPage(Url::to(['/cms/web-pages/index']));
$url = Url::to(['/cms/web-pages/delete', 'id' => 1], true);
$I->seeElement(['css' => 'a[href="' . $url . '"]']);
$I->click(['css' => 'a[href="' . $url . '"]']);
$I->acceptPopup();
$I->wait($wait);
$I->see('Suppression réussie');
$I->dontSeeElement(['css' => 'a[href="' . $url . '"]']);


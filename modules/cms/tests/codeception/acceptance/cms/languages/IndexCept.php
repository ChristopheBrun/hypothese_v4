<?php
use yii\helpers\Url;

/* @var $scenario Codeception\Scenario */
$I = new AcceptanceTester($scenario);
$I->wantTo('vérifier que la page cms/languages/index fonctionne bien');

$pageUrl = Url::to(['/cms/languages/index']);
$wait = 1; // secondes
$I->checkAccessFiltersAndLog($pageUrl, 'c.brun@hypothese.net', 'ew0oVQQkaCvCGwmIxK7a', 'Liste des langues');

$I->expect('la suppression fonctionne si la langue n\'est pas utilisée');
$url = Url::to(['/cms/languages/delete', 'id' => 3], true); // espagnol "http://127.0.0.1:8061/index-test.php/cms/languages/delete?id=3"
$I->seeElement(['css' => 'a[href="' . $url . '"]']);
$I->click(['css' => 'a[href="' . $url . '"]']);
$I->acceptPopup();
$I->wait($wait);
$I->see('Suppression réussie');
$I->dontSeeElement(['css' => 'a[href="' . $url . '"]']);

$I->expect('la suppression échoue si la langue est utilisée');
$url = Url::to(['/cms/languages/delete', 'id' => 1], true); // français
$I->seeElement(['css' => 'a[href="' . $url . '"]']);
$I->click(['css' => 'a[href="' . $url . '"]']);
$I->acceptPopup();
$I->wait($wait);
$I->see('Suppression impossible');
$I->seeElement(['css' => 'a[href="' . $url . '"]']);

$I->expect('le lien de création fonctionne');
$I->seeLink('Ajouter une langue');
$I->click('Ajouter une langue');
$I->see('Ajouter une langue', 'h1');

$I->expect('le lien de mise à jour fonctionne');
$I->amOnPage(Url::to(['/cms/languages/index']));
$url = Url::to(['/cms/languages/update', 'id' => 1], true); // français
$I->seeElement(['css' => 'a[href="' . $url . '"]']);
$I->click(['css' => 'a[href="' . $url . '"]']);
$I->wait($wait);
$I->see('Modifier une langue', 'h1');

$I->expect('le lien vers la fiche fonctionne');
$I->amOnPage(Url::to(['/cms/languages/index']));
$url = Url::to(['/cms/languages/view', 'id' => 1], true); // français
$I->seeElement(['css' => 'a[href="' . $url . '"]']);
$I->click(['css' => 'a[href="' . $url . '"]']);
$I->wait($wait);
$I->see('Fiche d\'une langue', 'h1');

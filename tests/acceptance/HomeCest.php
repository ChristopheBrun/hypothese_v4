<?php /** @noinspection PhpIllegalPsrClassPathInspection */

use yii\helpers\Url;

/**
 * Class IndexCest
 *
 * Test de la page de garde publique (avant authentification)
 */
class HomeCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/'));
    }

    public function ensureThatHomePageWorks(AcceptanceTester $I)
    {
//        $I->expectTo("être sur la page d'accueil du site");
//        $I->see('Accueil du site');
//
//        $I->expectTo("pouvoir aller sur la page de contact");
//        $I->seeLink('Contact');
//        $I->click('Contact');
//        $I->see('Votre nom');
    }
}

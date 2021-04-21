<?php /** @noinspection PhpIllegalPsrClassPathInspection */

use yii\helpers\Url;

/**
 * Class IndexCest
 *
 * Test de la page de garde publique (avant authentification)
 */
class IndexCest
{
    public function ensureThatHomePageWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/index'));
        $I->see('My Company');

        $I->seeLink('About');
        $I->click('About');
//        $I->wait(2); // wait for page to be opened

        $I->see('This is the About page.');
    }
}

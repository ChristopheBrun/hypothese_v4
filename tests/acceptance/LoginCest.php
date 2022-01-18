<?php /** @noinspection PhpIllegalPsrClassPathInspection */

use yii\helpers\Url;

class LoginCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/user/security/login'));
    }


    public function ensureThatLoginWorks(AcceptanceTester $I)
    {
        $I->see("Veuillez vous authentifier", 'h1');

        $I->amGoingTo('try to login with correct credentials');
        $I->fillField("Email", 'admin@test.net');
        $I->fillField("Mot de passe", 'test');
        $I->click("Connexion");
        $I->expectTo("Etre authentifié");
        $I->see("Vous êtes à présent connecté à votre compte utilisateur");
    }
}

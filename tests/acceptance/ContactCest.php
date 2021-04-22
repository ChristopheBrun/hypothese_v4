<?php /** @noinspection PhpIllegalPsrClassPathInspection */

use yii\helpers\Url;

class ContactCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/contact'));
    }

    public function contactPageWorks(AcceptanceTester $I)
    {
        $I->expectTo("être sur la page de contact");
        $I->wantTo('vérifier que la page de contact fonctionne');
        $I->see('Contact', 'h1');
        $I->dontSee('Veuillez saisir le code de vérification');
    }

    public function contactFormCanBeSubmitted(AcceptanceTester $I)
    {
        $I->amGoingTo('submit contact form with correct data');
        $I->see("Votre nom");
        $I->fillField("Votre nom", 'tester');
        $I->see("Votre adresse mail");
        $I->fillField("Votre adresse mail", 'tester@example.com');
        $I->see("Sujet");
        $I->fillField("Sujet", 'test subject');
        $I->see("Votre message");
        $I->fillField("Votre message", 'test content');

        $I->click("Envoyer le message");
        $I->dontSee("Votre formulaire est invalide");
        $I->see("Votre message a bien été envoyé");
    }
}

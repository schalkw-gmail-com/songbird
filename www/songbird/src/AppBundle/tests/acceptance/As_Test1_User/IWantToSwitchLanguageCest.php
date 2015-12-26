<?php
namespace As_Test1_User;
use \AcceptanceTester;
use \Common;

class IWantToSwitchLanguageCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    protected function login(AcceptanceTester $I, $username=TEST1_USERNAME, $password=TEST1_PASSWORD)
    {
        Common::login($I, $username, $password);
    }

    /**
     * Scenario 3.11
     * @before login
     */
    public function localeInFrench(AcceptanceTester $I)
    {
        // switch to french
        $I->selectOption('//select[@id="lang"]', 'fr');
        // I should be able to see "my profile" in french
        $I->canSee('Mon profil');
        $I->click('Mon profil');
        // now in show profile page
        $I->canSee("Liste d'actions");
        // now switch back to english
        $I->selectOption('//select[@id="lang"]', 'en');
        $I->canSee('My Profile');
    }
}

<?php
namespace As_test3_user;
use \AcceptanceTester;

class IWantToViewTheFrontendCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function homePageWorking(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->canSee('Welcome to SongBird CMS Demo');
        $I->canSeeElement('.jumbotron');
    }

    public function menusAreWorking(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->canSeeNumberOfElements('(//ul[@id="top_menu"]/li)[3]/ul/li', 2);
    }

    public function subPagesAreWorking(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('Contact');
        $I->canSee('This project is hosted in');
    }

    public function loginMenuWorking(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('Log in');
        $I->canSeeNumberOfElements('//ul[@id="top_menu"]/li', 3);
    }
}

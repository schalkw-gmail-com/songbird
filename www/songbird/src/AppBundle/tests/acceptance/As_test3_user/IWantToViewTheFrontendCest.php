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
        $I->executeInSelenium(
            function (\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver)
            {
                $webDriver->moveToElement(
                    $webDriver->findElement(\Facebook\WebDriver\WebDriverBy::linkText(
                        'About'
                    ))
                );

            }
        );

        $I->waitForText('Where do I start');
        $I->canSeeNumberOfElements('(//ul[@id="top_menu"])[3]/ul/li', 2);
    }
}

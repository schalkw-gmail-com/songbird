<?php
use \AcceptanceTester;

class AppBundleCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    #public function tryToTest(AcceptanceTester $I)
    #{
    #}
/*
    public function InstallationTest(AcceptanceTester $I)
    {
      $I->wantTo('Check if Symfony is installed successfully.');
      $I->amOnPage('/app/example');
      $I->see('Homepage');
    }
*/
    public function RemovalTest(AcceptanceTester $I)
{
    $I->wantTo('Check if app/example is not active.');
    $I->amOnPage('/app/example');
    $I->see('404 Not Found');
}	
}

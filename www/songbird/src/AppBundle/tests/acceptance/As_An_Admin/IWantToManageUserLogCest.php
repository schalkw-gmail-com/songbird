<?php
namespace As_An_Admin;
use \AcceptanceTester;
use \Common;

class IWantToAccessUserLogCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    protected function login(AcceptanceTester $I)
    {
        Common::login($I, ADMIN_USERNAME, ADMIN_PASSWORD);
    }

    /**
     * Scenario 15.1.1
     * @before login
     */
    public function listUserLog(AcceptanceTester $I) {
        $I->click('User Log');
        $I->waitForText('User Log List');
        $I->canSee('User Log List');
        $I->canSeeNumberOfElements('//table[@class="table table-bordered table-striped"]/tbody/tr', [1,30]);
    }

    /**
     * Scenario 15.1.2
     * @before login
     */
    public function showUserLog1(AcceptanceTester $I) {
        $I->click('User Log');
        $I->amOnPage('/admin/app/userlog/1/show');
        $I->canSee('/login');
    }

    /**
     * Scenario 15.1.3
     * @before login
     */
    public function createUserLog(AcceptanceTester $I) {
        require_once('IWantToManageAllUsersCest.php');
        IWantToManageAllUsersCest::createAndDeleteNewUser($I);
        // now look at the user log
        $I->click('User Log');
        // $I->executeJS('window.scrollTo(0,document.body.scrollHeight)');
        $I->canSee('/delete');
    }
    
}

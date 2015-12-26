<?php
namespace As_An_Admin;
use \AcceptanceTester;
use \Common;

class IWantToLoginCest
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
     * Scenario 1.21
     */
    public function wrongLoginCredentials(AcceptanceTester $I) {
        Common::login($I, ADMIN_USERNAME, '123');
        $I->canSee('Invalid credentials');
    }

    /**
     * Scenario 1.22
     * @before login
     */
    public function seeMyDashboardContent(AcceptanceTester $I) {
        $I->canSeeInCurrentUrl('/admin/dashboard');
        $I->CanSee('User Management'); 
        $I->CanSee('Dear admin');  
    }

    /**
     * Scenario 1.23
     * @before login
     */
    public function logoutSuccessfully(AcceptanceTester $I) {
        $I->click('Log out');
        // now user should be redirected to home page
        $I->seeCurrentUrlEquals('/app_dev.php/');
    }

    /**
     * Scenario 1.24
     */
    public function AccessAdminWithoutLoggingIn(AcceptanceTester $I) {
        $I->amOnPage('/admin');
        // now user should be redirected to login page
        $I->canSeeInCurrentUrl('/login');
    }
}

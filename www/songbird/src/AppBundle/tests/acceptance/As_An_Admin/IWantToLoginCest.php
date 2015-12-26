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
        $I->canSee('admin','//h3[@class="box-title"]');
    }

    /**
     * Scenario 1.23
     * @before login
     */
    public function logoutSuccessfully(AcceptanceTester $I) {
        $I->amOnPage('/logout');
        // now user should be redirected to home page
        $I->canSeeInCurrentUrl('/');
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

<?php
namespace As_An_Admin;
use \AcceptanceTester;
use \Common;

class IWantToManagePagesCest
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
     * Scenario 1.61
     * @before login
     */
    public function listPages(AcceptanceTester $I)
    {
        $I->click('Page Management');
        // go to user listing page
        $I->canSeeInCurrentUrl('/admin/app/user/list');

    }

    /**
     * Scenario 1.62
     * @before login
     */
    public function showContactUsPage(AcceptanceTester $I)
    {


    }

    /**
     * Scenario 1.62
     * @before login
     */
    public function reorderHome(AcceptanceTester $I)
    {


    }

    /**
     * Scenario 1.63
     * @before login
     */
    public function editHomepageMeta(AcceptanceTester $I)
    {
  
    }

    /**
     * Scenario 1.64
     * @before login
     */
    public function createAndDeleteTestPage(AcceptanceTester $I)
    {
        
    }
}

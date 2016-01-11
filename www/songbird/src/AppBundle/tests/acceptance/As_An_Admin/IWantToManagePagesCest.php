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
        // click on expand all
        $I->click('Expand All');
        $I->canSeeNumberOfElements('(//div[@id="nestable"]/ol/li)[2]/ol/li',2);
    }

    /**
     * Scenario 1.62
     * @before login
     */
    public function showContactUsPage(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/app/page/5/show');
        $I->canSee('contact_us');
        $I->canSee('Created');
    }

    /**
     * Scenario 1.62
     * Drag and drop is abit buggy. didn't drop to the right place.
     * @before login
     */
    public function reorderHome(AcceptanceTester $I)
    {
        $I->click('Page Management');
        // click on expand all
        $I->click('Expand All');
        $I->dragAndDrop('//li[@data-id="3"]', '//li[@data-id="5"]');
        $I->waitForText('reordered successfully');
        $I->canSeeNumberOfElements('(//div[@id="nestable"]/ol/li)[2]/ol/li',1);
        // now reorder it back
        $I->dragAndDrop('//li[@data-id="3"]', '//li[@data-id="4"]');
        $I->waitForText('reordered successfully');
        $I->canSeeNumberOfElements('(//div[@id="nestable"]/ol/li)[2]/ol/li',2);
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

<?php
namespace As_An_Admin;
use \AcceptanceTester;
use \Common;

class IWantToManageAllMediaCest
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
     * Scenario 14.2.1
     * @before login
     */
    public function viewMediaList(AcceptanceTester $I)
    {
        $I->click('Media Management');
        // go to user listing page
        $I->canSeeInCurrentUrl('/admin/app/media/list');
        $I->canSeeNumberOfElements('//table[@class="table table-bordered table-striped"]/tbody/tr',3);
    }

    /**
     * Scenario 14.2.2
     * @before login
     */
    public function showFile1(AcceptanceTester $I)
    {
        $I->click('Media Management');
        $I->waitForText('file 1');
        $I->click('file 1');
        $I->waitForElement('//input[contains(@id, "_name")]');
        $I->canSeeElement('//input[contains(@id, "_name")]');
    }

    /**
     * Scenario 14.2.3
     * @before login
     */
    public function editFile3(AcceptanceTester $I)
    {
        $I->click('Media Management');
        $I->waitForText('file 3');
        $I->click('file 3');
        
        $I->fillField('//input[contains(@id,"_name")]', 'file 3 - updated');
        $I->click('//button[@name="btn_update_and_list"]');
        $I->canSee('file 3 - updated');
        // now revert the changes
        $I->click('Media Management');
        $I->waitForText('file 3 - updated');
        $I->click('file 3 - updated');
        $I->fillField('//input[contains(@id,"_name")]', 'file 3');
        $I->click('//button[@name="btn_update_and_list"]');
        $I->canSee('file 3');
    }

    /**
     * Scenario 14.2.4
     * @before login
     */
    public function uploadAndDeleteMedia(AcceptanceTester $I)
    {
        $I->click('Media Management');
        $I->click('(//a[@data-toggle="dropdown"])[1]');
        $I->click('Add new');
        // upload file
        $I->waitForElementVisible('//a[contains(@href, "/app/media/create?provider=sonata.media.provider.image")]');
        $I->click('//a[contains(@href, "/app/media/create?provider=sonata.media.provider.image")]');
        $I->waitForElementVisible('//input[@type="file"]');
        $I->attachFile('//input[@type="file"]', 'testupload.png');
        $I->click('//button[@name="btn_create_and_list"]');
        $I->waitForText('has been successfully created');

        // now delete user
        // click on delete checkbox
        $I->click('//a[contains(@href, "/admin/app/media/list?context=default")]');
        $I->executeJS('window.scrollTo(0,document.body.scrollHeight)');
        $I->click('(//ins[@class="iCheck-helper"])[5]');
        // click on delete button
        $I->click('OK');
        // check we are on the right url
        $I->canSeeInCurrentUrl('/admin/app/media/batch');
        $I->click('Yes, execute');

        // I can no longer see gallery 4
        $I->click('Media Management');
        $I->cantSee('testupload.png');
    }
}

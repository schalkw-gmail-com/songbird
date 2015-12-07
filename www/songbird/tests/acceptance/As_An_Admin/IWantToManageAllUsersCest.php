<?php
namespace As_An_Admin;
use \AcceptanceTester;
use \Common;

class IWantToManageAllUsersCest
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
        $I->canSeeInCurrentUrl('/admin/dashboard');
    }

    /**
     * Scenario 1.61
     * @before login
     */
    public function listAllProfiles(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/app/user/list');
        $I->canSeeNumberOfElements('//table[@class="table table-bordered table-striped"]/tbody/tr',4);
    }

    /**
     * Scenario 1.62
     * @before login
     */
    public function showTest3User(AcceptanceTester $I)
    {
        // go to user listing page
        $I->amOnPage('/admin/app/user/list');
        // cick on show button
        $I->click('(//td[@class="sonata-ba-list-field sonata-ba-list-field-actions"])[4]/div/a[1]');
        $I->waitForText('test3@songbird.dev');
        $I->canSee('test3@songbird.dev');
        $I->canSeeInCurrentUrl('/user/4/show');
    }

    /**
     * Scenario 1.63
     * @before login
     */
    public function editTest3User(AcceptanceTester $I)
    {
        // go to user listing page
        $I->amOnPage('/admin/app/user/list');
        // click on edit button
        $I->click('(//td[@class="sonata-ba-list-field sonata-ba-list-field-actions"])[4]/div/a[2]');
        // check we are on the right url
        $I->canSeeInCurrentUrl('/app/user/4/edit');
        $I->fillField('//input[@value="test3 Lastname"]', 'lastname3 updated');
        // update
        $I->click('btn_update_and_edit');
        // go back to listing page
        $I->amOnPage('/admin/app/user/list');
        $I->canSee('lastname3 updated');
        // now revert username
        $I->amOnPage('/admin/app/user/4/edit');
        $I->fillField('//input[@value="lastname3 updated"]', 'test3 Lastname');
        $I->click('btn_update_and_edit');
        $I->amOnPage('/admin/app/user/list');
        $I->canSee('test3 Lastname');
    }

    /**
     * Scenario 1.64
     * @before login
     */
    public function createAndDeleteNewUser(AcceptanceTester $I)
    {
        // go to create page and fill in form
        $I->amOnPage('/admin/app/user/create');
        $I->fillField('//input[contains(@id, "_username")]', 'test4');
        $I->fillField('//input[contains(@id, "_email")]', 'test4@songbird.dev');
        $I->fillField('//input[contains(@id, "_plainPassword_first")]', 'test4');
        $I->fillField('//input[contains(@id, "_plainPassword_second")]', 'test4');
        // submit form
        $I->click('btn_create_and_edit');
        // go back to user list 
        $I->amOnPage('/admin/app/user/list');
        // i should see new test4 user created
        $I->canSee('test4@songbird.dev');
        
        // now delete user
         // go to user listing page
        $I->amOnPage('/admin/app/user/list');
        // click on edit button
        $I->click('(//td[@class="sonata-ba-list-field sonata-ba-list-field-actions"])[5]/div/a[3]');
        // check we are on the right url
        $I->canSeeInCurrentUrl('/app/user/5/delete');
        // click on delete button
        $I->click('//button[@type="submit"]');
        // go back to list page
        $I->amOnPage('/admin/app/user/list');
        // I can no longer see test4 user
        $I->cantSee('test4@songbird.dev');
    }
}

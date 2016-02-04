<?php
namespace As_Test1_User;
use \AcceptanceTester;
use \Common;


class IWantToManageMyOwnProfileCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    protected function login(AcceptanceTester $I)
    {
        Common::login($I, TEST1_USERNAME, TEST1_PASSWORD);
    }

    /**
     * Scenario 10.4.1
     * @before login
     */
    public function showMyProfile(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/app/user/2/show');
        $I->canSee('test1@songbird.dev');
        $I->canSee('Email');
    }

    /**
     * Scenario 10.4.2
     * @before login
     */
    public function hidUneditableFields(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/app/user/2/edit');
        // $I->click('(//a[@data-toggle="dropdown"])[1]');
        // $I->click('Edit');
        $I->cantSee('Enabled');
        $I->cantSee('Locked');
        $I->cantSee('Roles');
    }

    /**
     * Scenario 10.4.3
     * @before login
     */
    public function updateFirstnameOnly(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/app/user/2/edit');
        $I->fillField('//input[@value="test1 Lastname"]', 'lastname1 updated');
        // update
        $I->click('btn_update_and_edit');
        // There should be a flash div if update is successful
        $I->canSeeElement('//div[contains(@class, "alert-success")]');
        // now revert changes
        $I->amOnPage('/admin/app/user/2/edit');
        $I->fillField('//input[@value="lastname1 updated"]', 'test1 Lastname');
        // update
        $I->click('btn_update_and_edit');
    }

    /**
     * Scenario 10.4.4
     * @before login
     */
    public function updatePasswordOnly(AcceptanceTester $I)
    {

        $I->amOnPage('/admin/app/user/2/edit');
        $I->fillField('//input[contains(@id, "_plainPassword_first")]', '123');
        $I->fillField('//input[contains(@id, "_plainPassword_second")]', '123');

        // // update
        $I->click('btn_update_and_edit');
        $I->canSeeElement('//div[contains(@class, "alert-success")]');
        // // I should be able to login with the new password
        $I->amOnPage('/logout');
        Common::login($I, TEST1_USERNAME, '123');
        $I->canSeeInCurrentUrl('/admin/dashboard');

        // reset everything back
        $I->amOnPage('/admin/app/user/2/edit');
        $I->fillField('//input[contains(@id, "_plainPassword_first")]', TEST1_PASSWORD);
        $I->fillField('//input[contains(@id, "_plainPassword_second")]', TEST1_PASSWORD);
        $I->click('btn_update_and_edit');
        $I->canSeeElement('//div[contains(@class, "alert-success")]');
        // i should be able to login with the old password
        $this->login($I);
        $I->canSeeInCurrentUrl('/admin/dashboard');
    }
}

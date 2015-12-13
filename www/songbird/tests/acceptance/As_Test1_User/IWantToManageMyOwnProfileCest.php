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
     * Scenario 1.41
     * @before login
     */
    public function showMyProfile(AcceptanceTester $I)
    {
        $I->click('My Profile');
        $I->waitForText('test1@songbird.dev');
        $I->canSee('test1@songbird.dev');
        $I->canSee('Email');
    }

    /**
     * Scenario 1.42
     * @before login
     */
    public function hidUneditableFields(AcceptanceTester $I)
    {
        $I->click('My Profile');
        $I->click('(//a[@data-toggle="dropdown"])[2]');
        $I->click('Edit');
        $I->cantSee('Enabled');
        $I->cantSee('Locked');
        $I->cantSee('Roles');

    }

    /**
     * Scenario 1.43
     * @before login
     */
    public function updateFirstnameOnly(AcceptanceTester $I)
    {
        $I->click('My Profile');
        $I->click('(//a[@data-toggle="dropdown"])[2]');
        $I->click('Edit');
        $I->fillField('//input[@value="test1 Lastname"]', 'lastname1 updated');
        // update
        $I->click('btn_update_and_edit');
        // There should be a flash div if update is successful
        $I->waitForElement('//div[contains(@class, "alert-success")]');
        $I->canSeeElement('//div[contains(@class, "alert-success")]');

        // go to My profile and i should see the new last name
        $I->click('My Profile');
        $I->canSee('lastname1 updated');

        // db has been changed. now revert it back
        $I->click('My Profile');
        $I->click('(//a[@data-toggle="dropdown"])[2]');
        $I->click('Edit');
        $I->fillField('//input[@value="lastname1 updated"]', 'test1 Lastname'); 
        $I->click('btn_update_and_edit');
        $I->canSeeElement('//div[contains(@class, "alert-success")]');
    }

    /**
     * Scenario 1.44
     * @before login
     */
    public function updatePasswordOnly(AcceptanceTester $I)
    {

        $I->click('My Profile');
        $I->click('(//a[@data-toggle="dropdown"])[2]');
        $I->click('Edit');
        $I->fillField('//input[contains(@id, "_plainPassword_first")]', '123');
        $I->fillField('//input[contains(@id, "_plainPassword_second")]', '123');
        // // update
        $I->click('btn_update_and_edit');
        $I->canSeeElement('//div[contains(@class, "alert-success")]');
        // // I should be able to login with the new password
        Common::login($I, TEST1_USERNAME, '123');
        $I->canSeeInCurrentUrl('/admin/dashboard');

        $I->click('My Profile');
        $I->click('(//a[@data-toggle="dropdown"])[2]');
        $I->click('Edit');
        $I->fillField('//input[contains(@id, "_plainPassword_first")]', TEST1_PASSWORD);
        $I->fillField('//input[contains(@id, "_plainPassword_second")]', TEST1_PASSWORD);
        $I->click('btn_update_and_edit');
        $I->canSeeElement('//div[contains(@class, "alert-success")]');

        // i should be able to login with the old password
        $this->login($I);
        $I->canSeeInCurrentUrl('/admin/dashboard');
    }
}

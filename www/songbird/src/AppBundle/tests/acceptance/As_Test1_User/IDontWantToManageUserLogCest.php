<?php
namespace As_Test1_User;
use \AcceptanceTester;
use \Common;

class IDontWantToAccessUserLogCest
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
     * Scenario 15.2.1
     * @before login
     */
    public function listUserLog(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/app/user/list');
        $I->canSee('Access Denied');
    }

    /**
     * Scenario 15.2.2
     * @before login
     */
    public function showLog1(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/app/user/1/show');
        $I->canSee('Access Denied');
    }

    /**
     * Scenario 15.2.3
     * @before login
     */
    public function Editlog1(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/app/user/1/edit');
        $I->canSee('Access Denied');
    }
}

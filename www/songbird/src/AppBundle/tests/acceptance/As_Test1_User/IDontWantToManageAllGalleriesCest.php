<?php
namespace As_Test1_User;
use \AcceptanceTester;
use \Common;

class IDontWantToManageAllGalleriesCest
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
     * Scenario 4.31
     * @before login
     */
    public function viewGalleryList(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/app/gallery/list');
        $I->canSee('Access Denied');
    }

    /**
     * Scenario 4.32
     * @before login
     */
    public function showGallery1(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/app/gallery/3/show');
        $I->canSee('Access Denied');
    }

    /**
     * Scenario 4.33
     * @before login
     */
    public function editGallery3(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/app/gallery/3/edit');
        $I->canSee('Access Denied');
    }

}

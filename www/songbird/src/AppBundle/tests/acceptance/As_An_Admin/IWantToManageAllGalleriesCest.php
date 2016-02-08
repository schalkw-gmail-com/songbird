<?php
namespace As_An_Admin;
use \AcceptanceTester;
use \Common;

class IWantToManageAllGalleriesCest
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
     * Scenario 14.1.1
     * @before login
     */
    public function viewGalleryList(AcceptanceTester $I)
    {
        $I->click('Gallery Management');
        // go to user listing page
        $I->canSeeInCurrentUrl('/admin/app/gallery/list');
        $I->canSeeNumberOfElements('//table[@class="table table-bordered table-striped"]/tbody/tr',3);
    }

    /**
     * Scenario 14.1.2
     * @before login
     */
    public function showGallery1(AcceptanceTester $I)
    {
        $I->click('Gallery Management');
        $I->click('gallery 1');
        $I->waitForText('file 1');
        $I->canSee('file 1');
        $I->canSeeElement('//input[contains(@id, "_name")]');
        

    }

    /**
     * Scenario 14.1.3
     * @before login
     */
    public function editGallery3(AcceptanceTester $I)
    {
        $I->click('Gallery Management');
        // click on edit link
        $I->click('gallery 3');
        
        $I->fillField('//input[contains(@id,"_name")]', 'gallery 3 - updated');
        $I->click('//button[@name="btn_update_and_list"]');
        $I->canSee('gallery 3 - updated');

        // reverting it back.
        $I->click('Gallery Management');
        // click on edit link
        $I->click('gallery 3');
        $I->fillField('//input[contains(@id,"_name")]', 'gallery 3');
        $I->click('//button[@name="btn_update_and_list"]');
        $I->canSee('gallery 3');
    }

    /**
     * Scenario 14.1.4
     * @before login
     */
    public function AddAndDeleteMediaUnderGallery3(AcceptanceTester $I)
    {
        $I->click('Gallery Management');
        // click on edit link
        $I->click('gallery 3');
        $I->click('//a[contains(@href, "/app/galleryhasmedia/create")]');
        // wait for second element to appear
        $I->waitForElementVisible('(//a[contains(@href, "/app/media/create")])[2]');
        // click on second element "add new" button
        $I->click('(//a[contains(@href, "/app/media/create")])[2]');

        // upload file
        $I->waitForElementVisible('//a[contains(@href, "/app/media/create?provider=sonata.media.provider.image")]');
        $I->click('//a[contains(@href, "/app/media/create?provider=sonata.media.provider.image")]');
        $I->waitForElementVisible('//input[@type="file"]');
        $I->attachFile('//input[@type="file"]', 'testupload.png');
        $I->click('//button[@name="btn_create"]');
        $I->waitForText('testupload.png');
        // update list
        $I->click('//button[@name="btn_update_and_edit"]');
        $I->waitForText('has been successfully updated');
        $I->canSee('has been successfully updated');
        
        // now check work
        $I->click('Gallery Management');
        $I->click('gallery 3');
        $I->waitForText('testupload.png');
        $I->canSee('testupload.png');

        // now delete file. Note that deleting this file doesn't delete the real file, it only deletes the relationship.
        $I->click('(//ins[@class="iCheck-helper"])[3]');
        $I->click('//button[@name="btn_update_and_edit"]');
        
        // make sure file is no more
        // now check work
        $I->click('Gallery Management');
        $I->click('gallery 3');
        $I->cantSee('testupload.png');

        // now we need to check that the media file is uploaded under the media section.
        $I->click('Media Management');
        $I->waitForText('testupload.png');
        $I->canSee('testupload.png');

        // we now need to remove it.
        // scroll to bottom if you screen is too small
        $I->executeJS('window.scrollTo(0,document.body.scrollHeight)');
        $I->click('(//ins[@class="iCheck-helper"])[5]');
        $I->click('OK');
        $I->canSeeInCurrentUrl('/admin/app/media/batch');
        $I->click('Yes, execute');
        $I->cantSee('testupload.png');
    }

    /**
     * Scenario 14.1.5
     * @before login
     */
    public function AddAndDeleteNewGallery(AcceptanceTester $I)
    {
        $I->click('Gallery Management');
        $I->click('(//a[@data-toggle="dropdown"])[1]');
        $I->click('Add new');
        $I->fillField('//input[contains(@id, "_name")]', 'gallery 4');
        // submit form
        $I->click('btn_create_and_list');

        // i should see new gallery 4 created
        $I->canSee('gallery 4');

        // now delete user
        // scroll to bottom if you screen is too small
        $I->executeJS('window.scrollTo(0,document.body.scrollHeight)');
        // click on delete checkbox
        $I->click('(//ins[@class="iCheck-helper"])[5]');
        // click on delete button
        $I->click('OK');
        // check we are on the right url
        $I->canSeeInCurrentUrl('/admin/app/gallery/batch');
        $I->click('Yes, execute');

        // I can no longer see gallery 4
        $I->cantSee('gallery 4');
    }

}

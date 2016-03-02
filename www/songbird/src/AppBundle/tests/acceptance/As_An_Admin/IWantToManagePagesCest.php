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
        $I->click('Page Management');
        $I->click('home');
        $I->fillField('//input[contains(@id, "_slug")]', 'home1');
        $I->canSeeNumberOfElements('//select[contains(@id,"_0_locale")]/option',2);
        // ckeeditor should be loaded
        $I->canSeeInPageSource('cke_1_top');
        $I->click('btn_update_and_edit');
        $I->canSee('has been successfully updated.');
        // now update file back to home
        $I->fillField('//input[contains(@id, "_slug")]', 'home');
        $I->click('btn_update_and_edit');
        $I->canSee('has been successfully updated.');
    }

    /**
     * Scenario 1.64
     * @before login
     */
    public function createAndDeleteTestPage(AcceptanceTester $I)
    {

        // create the page
        $I->click('Page Management');
        $I->click('Add new');
        $I->fillField('//input[contains(@id, "_slug")]', 'pagetest');
        $I->fillField('//input[contains(@id, "_sequence")]', '4');
        $I->click('btn_create_and_edit');
        $I->canSee('has been successfully created.');

        // create the pagemeta
        $I->click('//a[contains(@href, "/pagemeta/create")]');
        $I->waitForElement('//input[contains(@id, "_pageMetas_0_menu_title")]');
        $I->fillField('//input[contains(@id, "_0_menu_title")]', 'menu test 0');
        $I->fillField('//input[contains(@id, "_0_page_title")]', 'page test 0');
        $I->selectOption('//select[contains(@id, "_0_locale")]', 'fr');
        // codeception selectIFrame is not good. We will use facebook driver
        $I->executeInSelenium(
            function (\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver)
            {
                $webDriver->switchTo()->frame(
                    $webDriver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector(
                        '.cke_wysiwyg_frame'
                    ))
                );

                $webDriver->executeScript(
                    'arguments[0].innerHTML = "' . addslashes('<p>this is test content</p>') . '"',
                    [$webDriver->findElement(\Facebook\WebDriver\WebDriverBy::tagName('body'))]
                );

                $webDriver->switchTo()->defaultContent();
            }
        );
        // click submit and test everything saving correctly.
        $I->click('btn_update_and_edit');
        $I->canSee('has been successfully updated.');
        $I->canSeeInPageSource('menu test 0');
        $I->seeOptionIsSelected('//select[contains(@id, "_0_locale")]', 'fr');
        $I->canSeeInPageSource('this is test content');
        
        // now let us create another pagemeta
        $I->click('//a[contains(@href, "/pagemeta/create")]');
        $I->waitForElement('//input[contains(@id, "_pageMetas_1_menu_title")]');
        $I->fillField('//input[contains(@id, "_1_menu_title")]', 'menu test 1');
        $I->fillField('//input[contains(@id, "_1_page_title")]', 'page test 1');
        // click submit and test everything saving correctly.
        $I->click('btn_update_and_edit');
        $I->canSeeInPageSource('menu test 1');
        // delete new meta and item should be gone
        $I->click('(//ins[@class="iCheck-helper"])[2]');
        $I->click('btn_update_and_edit');
        $I->canSee('has been successfully updated.');
        $I->cantSeeInPageSource('menu test 1');
        // return back to page listing and click delete
        $I->click('Page Management');
        $I->click('//a[contains(@href,"/app/page/6/delete")]');

        $I->click('//button[@type="submit"]');
        $I->waitForText('contact_us');
        $I->cantSee('pagetest');
        // we also make sure pagemata id 11 is no longer avaliable
        $I->amOnPage('/admin/app/pagemeta/11/show');
        $I->canSee('unable to find the object with id');
    }
}

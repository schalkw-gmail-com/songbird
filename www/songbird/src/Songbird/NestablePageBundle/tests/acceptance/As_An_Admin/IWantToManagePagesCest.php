<?php
namespace As_An_Admin;
use \AcceptanceTester;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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
        $session = $I->grabServiceFromContainer('session');

        $firewall = 'secured_area';
        $token = new UsernamePasswordToken('admin', 'admin', $firewall, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();
        
        
        $cookie = new Cookie($session->getName(), $session->getId());
        
        $this->client->getCookieJar()->set($cookie);
        $I->amOnPage('/admin');
        // we are in.
        $I->canSee('Welcome to SongBird CMS');
       
    }

    /**
     * scenario 6.11
     * @before login
     */
    public function listPages(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        // we are in.
        $I->canSee('Welcome to SongBird CMS');
    }
}

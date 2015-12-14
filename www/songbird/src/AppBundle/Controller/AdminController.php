<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class AdminController extends Controller
{

    /**
     * Redirects user based on their referer
     *
     * @Route("/{_locale}/locale", name="core_set_locale")
     * @Method("GET")
     * @Template()
     */
    public function setLocaleAction(Request $request, $_locale)
    {
        $auth_checker = $this->get('security.authorization_checker');

        // if referrer exists, redirect to referrer
        $referer = $request->headers->get('referer');
        if ($referer) {
            return $this->redirect($referer);
        }
        // if logged in, redirect to admin
        elseif ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('sonata_admin_dashboard');
        }
        // else redirect to homepage
        else {
            return $this->redirect('/');
        }
    }

}

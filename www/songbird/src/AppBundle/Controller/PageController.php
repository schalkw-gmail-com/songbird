<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    /**
     * @Route("/{slug}", name="app_page_index", requirements = {"slug" = "^((|home)$)"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
    	$page = $this->getDoctrine()->getRepository('AppBundle:Page')->findPageMetaByLocale('home', $this->get('request')->getLocale());
        $rootMenuItems = $this->getDoctrine()->getRepository('AppBundle:Page')->findParent();

    	return array(
    		'page' => $page,
            'tree' => $rootMenuItems,
    	);
    }


    /**
     * @Route("/{slug}", name="app_page_view")
     * @Template()
     */
    public function viewAction(Request $request, $slug)
    {

    	$page = $this->getDoctrine()->getRepository('AppBundle:Page')->findPageMetaByLocale($slug, $this->get('request')->getLocale());
        $rootMenuItems = $this->getDoctrine()->getRepository('AppBundle:Page')->findParent();

        return array(
            'page' => $page,
            'tree' => $rootMenuItems,
        );    
    }
}

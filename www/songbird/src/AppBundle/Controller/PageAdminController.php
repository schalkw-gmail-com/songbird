<?php

namespace AppBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;

class PageAdminController extends CRUDController
{
	/**
     * List action
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function listAction()
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        // $rootMenuItems = $em->getRepository('SongbirdPageBundle:Page')->findParentByLocale($this->get('request')->getLocale());
        $rootMenuItems = $this->getDoctrine()->getRepository('AppBundle:Page')->findParent();

        return $this->render('AppBundle:Admin:list.html.twig', array(
            'action'     => 'list',
            'tree' => $rootMenuItems,
            'form'       => $formView,
            'datagrid'   => $datagrid,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
        ));
    }

    /**
     * reorder action
     *
     * @return JsonResponse
     */
    public function reorderAction()
    {
        // id of affected element
        $id = $this->get('request')->get('id');
        // parent Id
        $parentId = ($this->get('request')->get('parentId') == '') ? null : $this->get('request')->get('parentId');
        // new sequence of this element. 0 means first element.
        $position = $this->get('request')->get('position');

        $result = $this->getDoctrine()->getRepository('AppBundle:Page')->reorderElement($id, $parentId, $position); 

        return new JsonResponse(
            array('message' => $this->get('translator')->trans($result[0], array(), 'BpehNestablePageBundle')
, 'success' => $result[1])
        );

    }
}

<?php

namespace Songbird\NestablePageBundle\Controller;

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

        $em = $this->getDoctrine()->getManager();

        $rootMenuItems = $em->getRepository('SongbirdNestablePageBundle:Page')->findParentByLocale($this->get('request')->getLocale());

        return $this->render('SongbirdNestablePageBundle:Admin:list.html.twig', array(
            'action'     => 'list',
            'tree' => $rootMenuItems,
            'form'       => $formView,
            'datagrid'   => $datagrid,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
        ));
    }


    public function reorderAction()
    {
        $em = $this->getDoctrine()->getManager();
        // id of affected element
        $id = $this->get('request')->get('id');
        // parent Id
        $parentId = ($this->get('request')->get('parentId') == '') ? null : $this->get('request')->get('parentId');
        // new sequence of this element. 0 means first element.
        $position = $this->get('request')->get('position');
        // step 1: get all siblings based on old location. update the seq
        $old_item = $em->getRepository('SongbirdNestablePageBundle:Page')->findOneById($id);
        $old_parent_id = ($old_item->getParent() === null) ? '' : $old_item->getParent()->getId();
        
        // if old parent and new parent is the same, user moving in same level.
        // dont need to update old parent
        if ($old_parent_id != $parentId) {
            $old_children = $em->getRepository('SongbirdNestablePageBundle:Page')->findBy(
                array('parent' => $old_parent_id),
                array('sequence' => 'ASC')
                );
            $seq = 0;

            foreach ($old_children as $oc) {
                $or = $em->getRepository('SongbirdNestablePageBundle:Page')->findOneById($oc->getId());
                if ($old_item->getSequence() != $or->getSequence()) {
                    $or->setSequence($seq);
                    $em->persist($or);
                    $seq++;
                }
            }
        }
        
        $new_children = $em->getRepository('SongbirdNestablePageBundle:Page')->findBy(
            array('parent' => $parentId),
            array('sequence' => 'ASC')
            );
        $seq = 0;
        $ir = $em->getRepository('SongbirdNestablePageBundle:Page')->findOneById($id);
        if (!is_null($parentId)) {
            $parent = $em->getRepository('SongbirdNestablePageBundle:Page')->findOneById($parentId);
            $ir->setParent($parent);
        }
        else {
            $ir->setParent(null); 
        }

        foreach ($new_children as $nc) {
            // if the id is the same, it means user moves in same level

            if ($old_parent_id == $parentId) {
                // if in same level, we just need to swap position
                // get id of element with the current position then swap it
                $nr = $em->getRepository('SongbirdNestablePageBundle:Page')->findBy(
                    array('sequence' => $position, 'parent' => $parentId)
                    );

                $nr[0]->setSequence($ir->getSequence());
                $em->persist($nr[0]);
                $ir->setSequence($position);
                $em->persist($ir);
                break;
            }
            // user drag from one level to the next, it is a new addition
            else {

                if ($position == $seq) {
                    $ir->setSequence($seq);
                    $em->persist($ir);
                    $seq++;
                }

                $nr = $em->getRepository('SongbirdNestablePageBundle:Page')->findOneById($nc->getId());
                $nr->setSequence($seq);
                $em->persist($nr);
                
            }
            
            $seq++;
        }

        // if its the last entry and user moved to new level
        if ($old_parent_id != $parentId && $position == count($new_children)) {
            $ir->setSequence($seq);
            $em->persist($ir);
        }

        $message = '';
        $success = true;

        // step 3: run a loop, insert the new element and update the seq
        try {
            $em->flush();
            $em->clear(); // prevent doctrine from caching     
            // $this->getRequest()->getSession()->getFlashBag()->add("success", "flash_edit_success");        
            $message = $this->get('translator')->trans('flash_reorder_edit_success', array(), 'page');
        }
        catch (\Exception $e) {
            $message = $e->getMessage();
            $success = false;
        }

        return new JsonResponse(
            array('message' => $message, 'success' => $success)
        );

    }
}

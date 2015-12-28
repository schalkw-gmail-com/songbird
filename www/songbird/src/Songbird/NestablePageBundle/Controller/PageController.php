<?php

namespace Songbird\NestablePageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Songbird\NestablePageBundle\Entity\Page;
use Songbird\NestablePageBundle\Form\PageType;

/**
 * Page controller.
 *
 * @Route("/page")
 */
class PageController extends Controller
{

    /**
     * Lists all Page entities.
     *
     * @Route("/", name="page")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $rootMenuItems = $em->getRepository('SongbirdNestablePageBundle:Page')->findParentByLocale($this->get('request')->getLocale());

        return array(
            'tree' => $rootMenuItems,
        );
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
    
    /**
     * Creates a new Page entity.
     *
     * @Route("/", name="page_create")
     * @Method("POST")
     * @Template("SongbirdNestablePageBundle:Page:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Page();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('page_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Page entity.
     *
     * @param Page $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Page $entity)
    {
        $form = $this->createForm(new PageType(), $entity, array(
            'action' => $this->generateUrl('page_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Page entity.
     *
     * @Route("/new", name="page_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Page();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Page entity.
     *
     * @Route("/{id}", name="page_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SongbirdNestablePageBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Page entity.
     *
     * @Route("/{id}/edit", name="page_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SongbirdNestablePageBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Page entity.
    *
    * @param Page $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Page $entity)
    {
        $form = $this->createForm(new PageType(), $entity, array(
            'action' => $this->generateUrl('page_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Page entity.
     *
     * @Route("/{id}", name="page_update")
     * @Method("PUT")
     * @Template("SongbirdNestablePageBundle:Page:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SongbirdNestablePageBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('page_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Page entity.
     *
     * @Route("/{id}", name="page_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SongbirdNestablePageBundle:Page')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Page entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('page'));
    }

    /**
     * Creates a form to delete a Page entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('page_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

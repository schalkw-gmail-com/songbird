<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use FOS\UserBundle\Model\UserManagerInterface;

class UserAdmin extends Admin
{
    private $userManager;

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('username')
            ->add('email')
            ->add('firstname')
            ->add('lastname')
            ->add('enabled')
            ->add('lastLogin')
            ->add('locked')
            ->add('roles')
            ->add('modified')
            ->add('created')
        ;
    }


    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('firstname')
            ->add('lastname')
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('locked')
            ->add('roles')
            ->add('modified')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        // if its an edit route, make password non-compulsory
        $passwordRequired = (preg_match('/_edit$/', $this->getRequest()->get('_route'))) ? false : true;

        $formMapper
            ->add('firstname')
            ->add('lastname')
            ->add('username')
            ->add('email')
            ->add('enabled', 'checkbox', array(
                'label' => 'Account Enabled',
                'required' => false
                ))
            ->add('locked', 'checkbox', array(
                'label' => 'Account Locked',
                'required' => false
                ))
            ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'The password fields must match.',
                    'required' => $passwordRequired,
                    'first_options'  => array('label' => 'Password'),
                    'second_options'=> array('label' => 'Repeat Password'),
                ))
           ->add('roles')
        ;
    }


    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('firstname')
            ->add('lastname')
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('lastLogin')
            ->add('locked')
            ->add('roles');
    }

    public function preUpdate($user)
    {
        $this->getUserManager()->updateCanonicalFields($user);
        $this->getUserManager()->updatePassword($user);
    }

    /**
     * setUserManager
     * @param UserManagerInterface $userManager fosuserbundle user manager
     */
    public function setUserManager(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->userManager;
    }
}

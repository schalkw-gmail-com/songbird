<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PageMetaAdmin extends Admin
{

    protected $baseRouteName = 'admin_pagemeta';
    
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('menu_title')
            ->add('page_title')
            ->add('locale', 'locale')
            ->add('short_description', 'text', array(
                'required' => false))
            ->add('content', 'textarea', array(
                'required' => false));
    }
}

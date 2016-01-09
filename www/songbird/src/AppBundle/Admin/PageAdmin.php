<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class PageAdmin extends Admin
{
    
    protected $baseRouteName = 'admin_page';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('reorder'); // Action gets added automatically
    }
    
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('slug')
            ->add('isPublished')
            ->add('sequence')
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
            ->add('slug')
            ->add('isPublished')
            ->add('sequence')
            ->add('modified')
            ->add('created')
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
        // define group zoning
        $formMapper
            ->with($this->trans('PageMetas'), array('class' => 'col-md-9'))->end()
            ->with($this->trans('Options'), array('class' => 'col-md-3'))->end()
        ;

        $formMapper
            ->with('Options')
            ->add('slug')
            ->add('sequence')
            ->add('isPublished')    
            ->end()
            ->with('PageMetas')
                ->add('pageMetas', 'sonata_type_collection', array(
                        'cascade_validation' => true,
                    ), array(
                        'edit'              => 'inline',
                        'inline'            => '',
                        'sortable'          => 'position',
                        'admin_code'        => 'app.admin.pagemeta'
                    )
                )
            ->end()
        ;

    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('slug')
            ->add('isPublished')
            ->add('sequence')
            ->add('modified')
            ->add('created')
        ;
    }

    public function preUpdate($page)
    {
        // how do you get the _delete from the form submitted? this should be an automated process.
        $ids = array();
        // only these ids need to be updated, ie without the deleted checkbox checked 
        foreach ($page->getPageMetas() as $pm) {
            $page->addPageMeta($pm->setPage($page));
            $ids[] = $pm->getId();
        }

        // all the rest we delete
        $page_id = $this->getRequest()->attributes->get('id');
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();

        $qb = $em->createQueryBuilder();

        $qb = $qb->delete('AppBundle:PageMeta', 'pm');

        if (count($ids) > 0) {
            $qb = $qb->where($qb->expr()->notIn('pm.id', $ids));
        }

        $qb = $qb->andWhere($qb->expr()->eq('pm.page', $page_id));
        $query = $qb->getQuery();
        $query->execute();
    }  
}

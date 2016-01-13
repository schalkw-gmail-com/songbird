<?php

namespace AppBundle\Twig\Extension;

/**
 * Twig Extension to get Menu title based on locale
 */
class MenuLocaleTitle extends \Twig_Extension
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct($em, $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function getName()
    {
        return 'menu_locale_title_extension';
    }

    public function getFunctions()
    {
        return array(
            'getMenuLocaleTitle' => new \Twig_Function_Method($this, 'getMenuLocaleTitle')
        );
    }

    public function getMenuLocaleTitle($slug)
    {
        return $this->em->getRepository('AppBundle:Page')->findPageMetaByLocale($slug, $this->container->get('request')->getLocale())->getPageMetas()[0]->getMenuTitle();
    }
}

<?php

namespace AppBundle\Event;

use Sonata\AdminBundle\Event\ConfigureEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\Container;

class UserCustomAction
{
    /**
     * @var Container
     */
    private $container;

    /**
     * Constructor
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * show an error if user is not superadmin and tries to manage other user details
     * 
     * @param  ConfigureEvent $event sonata admin event
     * @return null
     */
    public function sonataAdminCheckUserRights(ConfigureEvent $event)
    {
        // ignore this if user is admin
        if ($event->getAdmin()->isGranted('ROLE_SUPER_ADMIN')) {
            return;
        }

        // $user_id = $event->getAdmin()->getRequest()->attributes->all()['id'];
        // $session_id = $event->getAdmin()->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser()->getId();
        $user_id = $this->container->get('request')->attributes->all()['id'];
        $session_id = $this->container->get('security.context')->getToken()->getUser()->getId();
        
        if ($user_id != $session_id) {
            throw new AccessDeniedException();
        }
    }

    /**
     * Redirect user to another page after password reset is success
     * 
     * @param  Configure $event GetResponseUserEvent
     * @return null
     */
    public function redirectUserAfterPasswordReset(FormEvent $event)
    {
        $url = $this->container->get('router')->generate('sonata_admin_dashboard');
        $event->setResponse(new RedirectResponse($url));
    }
}


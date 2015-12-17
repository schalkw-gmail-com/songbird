<?php

namespace AppBundle\Event;

use Sonata\AdminBundle\Event\ConfigureEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use AppBundle\Entity\UserLog;

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

    public function userLog(GetResponseEvent $event)
    { 
        // check if user is logged in, if not, return
        $request = $event->getRequest();
        $current_url = $request->server->get('REQUEST_URI');
        $admin_path = $this->container->getParameter('admin_path');

        // only log admin area and only if user is logged in. Dont log search by filter
        if (!is_null($this->container->get('security.context')->getToken()) && preg_match('/\/'.$admin_path.'\//', $current_url)
            && ($request->query->get('filter') === null) && !preg_match('/\/userlog\//', $current_url)) {
        
            $em = $this->container->get('doctrine.orm.entity_manager');
            $log = new UserLog();
            $log->setData(json_encode($request->request->all()));
            $log->setUser($this->container->get('security.token_storage')->getToken()->getUser());
            $log->setCurrentUrl($current_url);
            $log->setReferrer($request->server->get('HTTP_REFERER'));
            $log->setAction($request->getMethod());
            $log->setCreated(new \DateTime('now'));
            $em->persist($log);
            $em->flush();
        }
    }
}


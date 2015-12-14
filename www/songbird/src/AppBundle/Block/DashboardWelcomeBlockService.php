<?php

namespace AppBundle\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class DashboardWelcomeBlockService extends BaseBlockService
{
	protected $pool;

    /**
     * @param string $name
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     */
    public function __construct($name, EngineInterface $templating)
    {
        parent::__construct($name, $templating);

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
	{
	    $resolver->setDefaults(array(
	        'heading'    => 'dashboard.welcome.title',
	        'template' => 'AppBundle:Block:dashboard_welcome_block_service.html.twig',
	    ));
	}

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $settings = $blockContext->getSettings();

        return $this->renderPrivateResponse($blockContext->getTemplate(), array(
            'block'         => $blockContext->getBlock(),
            'settings'      => $settings,
        ), $response);

    }
}

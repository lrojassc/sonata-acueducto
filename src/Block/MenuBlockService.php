<?php

namespace App\Block;

use Knp\Menu\Provider\MenuProviderInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

class MenuBlockService extends AbstractBlockService
{

    private $menuProvider;

    public function __construct(Environment $twig, MenuProviderInterface $menuProvider)
    {
        parent::__construct($twig);
        $this->menuProvider = $menuProvider;
    }

    public function execute(BlockContextInterface $blockContext, ?Response $response = NULL): Response
    {
        $menu = $this->menuProvider->get($blockContext->getSetting('menu_name'));

        return $this->renderResponse($blockContext->getTemplate(), [
            'menu' => $menu,
            'block' => $blockContext->getBlock(),
            'settings' => $blockContext->getSettings(),
        ], $response);
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'menu_name' => 'main',
            'template' => 'Menu/block_menu.html.twig',
        ]);
    }
}
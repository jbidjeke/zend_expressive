<?php

namespace App\Action;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class AdvertFactory
{
    public function __invoke(ContainerInterface $container)
    {
		$config   = $container->get('config');
        $router   = $container->get(RouterInterface::class);
		
        return new AdvertAction($config, $router, null);
    }
}

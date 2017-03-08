<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Interop\Container;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use App\Model\Adverts;

class AdvertAction
{
    private $router;

    private $template;
	
	private $config;

    public function __construct(ArrayObject $config, Router\RouterInterface $router, Template\TemplateRendererInterface $template = null)
    {   
        $this->router   = $router;
        $this->template = $template;
		$this->config = $config;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
		$parram = $request->getQueryParams();
		
        $adverts = new Adverts($this->config);
		$adverts->lat = $parram['lat'] ?? 48.451580799999995;
		$adverts->lng = $parram['lng'] ?? 6.744914999999992;
		$adverts->category = $parram['category'] ?? 'annonces';
		$adverts->distance = $parram['distance'] ?? 5000;
		$adverts->q = $parram['q'] ?? "";  
		$adverts->getAround();
		$adverts->toArray();
		
		return new JsonResponse($adverts->resultDb, 200, ['Access-Control-Allow-Origin' => '*']);
        
    }
}

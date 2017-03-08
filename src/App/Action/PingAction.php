<?php

namespace App\Action;

use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
//use App\Model\Adverts;

class PingAction
{
	private $config;
	
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
		//$container = new ServiceManager();
		//$config = $container->get('config');
        /*$config   = $container->get('config');
		$parram = $request->getQueryParams();
		
        $adverts = new Adverts($config);
		
		$adverts->lat = isset($parram['lat']) ?  $parram['lat'] : 48.451580799999995;
		$adverts->lng = isset($parram['lng']) ?  $parram['lng'] : 6.744914999999992;
		$adverts->category = isset($parram['category']) ?  $parram['category'] : 'annonces';
		$adverts->distance = isset($parram['distance']) ?  $parram['distance'] : 5000;
		$adverts->q = isset($parram['q']) ?  $parram['q'] : "";  
		$adverts->getAround();
		$adverts->toArray();
		
		return new JsonResponse($adverts->resultDb);*/
		
		return new JsonResponse(['ack' => time()]);
    }
}

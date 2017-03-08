<?php
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\AppFactory;
use App\Model\Adverts;
// Delegate static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server'
    && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
) {
    return false;
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
//$container = require 'config/container.php';

/** @var \Zend\Expressive\Application $app */
/*$app = $container->get(\Zend\Expressive\Application::class);
$app->run();*/

$config = require 'config/config.php';
$adverts = new Adverts($config);

$app = AppFactory::create();
$app->get('/expressive/', function ($request, $response, $next) {
    $response->getBody()->write('Hello, world!');
    return $response;
});

$app->get('/expressive/adverts', function ($request, $response, $next) use ($adverts) {
	$parram = $request->getQueryParams();
	
    $adverts->lat = $parram['lat'] ?? 48.451580799999995;
    $adverts->lng = $parram['lng'] ?? 6.744914999999992;
    $adverts->category = $parram['category'] ?? 'annonces';
    $adverts->distance = $parram['distance'] ?? 5000;
    $adverts->q = $parram['q'] ?? "";  
    $adverts->getAround();
    $adverts->toArray();
	
    return new JsonResponse($adverts->resultDb);

});

$app->get('/expressive/adverts/:lat/:lng/:category/:distance/:q', function ($request, $response, $next) use ($adverts) {
    $adverts->lat = $request -> getAttribute('lat');
    $adverts->lng = $request -> getAttribute('lng');
    $adverts->category = $request -> getAttribute('category');
    $adverts->distance = $request -> getAttribute('distance');
    $adverts->q = $request -> getAttribute('q');
    $adverts->getAround();
    $adverts->toArray();
    return new JsonResponse($adverts->resultDb);
	
});

$app->get('/expressive/advert{/id}', function ($request, $response, $next) {
    $response->getBody()->write('get');
    return $response;
});

$app->pipeRoutingMiddleware();
$app->pipeDispatchMiddleware();
$app->run();
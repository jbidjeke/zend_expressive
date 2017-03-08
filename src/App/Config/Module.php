<?php
namespace App\Config;

class Module {
    public function __invoke(){ 
        $cachedConfigFile = __DIR__."/../../../data/cache/app_config.php";
		if (is_file($cachedConfigFile))
		   return include realpath($cachedConfigFile);	
        else
		   return include realpath(__DIR__."/../../../config/config.php");
    }
}

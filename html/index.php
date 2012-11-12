<?php
$time = microtime();
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

define("BASE_PATH", getenv('BASE_PATH'));
define("CACHE_PATH", BASE_PATH.'/cms-misc/cache');

include BASE_PATH.'/inc/Zend/Loader/StandardAutoloader.php';

$autoLoader = new Zend\Loader\StandardAutoloader(array(
    'namespaces' => array(
        'Zend'	=> BASE_PATH.'/inc/Zend',
		'Core'	=> BASE_PATH.'/inc/Core',
		'Fucms' => BASE_PATH.'/library-cms/Fucms',
		'Brick' => BASE_PATH.'/extension/Brick'
    ),
    'prefixes' => array(
    	'Twig'	=> BASE_PATH.'/inc/Twig',
    	'App'	=> BASE_PATH.'/inc/App',
    	'Class' => BASE_PATH.'/library-cms/Class'
    )
));
$autoLoader->register();

$serverConfig = include 'config/server.config.php';
\Fucms\Site\Config::config($serverConfig);

$application = Zend\Mvc\Application::init(include 'config/application.config.php')->run();

$finishTime = microtime();
//echo $finishTime - $time;





<?php
$time = microtime();

/**
 * Validate Site Domains!
 * 
 */
$requestHost = $_SERVER['HTTP_HOST'];
$m = new Mongo('127.0.0.1', array('persist' => 'x'));
$db = $m->selectDb('server_center');
$siteArr = $db->site->findOne(array('domain' => $requestHost));
if(is_null($siteArr)) {
	header('Location: http://www.enorange.com/no-site/');
	exit(0);
}
if(!$siteArr['active']) {
	header('Location: http://www.enorange.com/site-expired/');
	exit(0);
}

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
$siteConfig = new \Fucms\SiteConfig($serverConfig['enviroment'], $serverConfig['lib'], $siteArr);

$application = Zend\Mvc\Application::init(include 'config/application.config.php');
$serviceManager = $application->getServiceManager();
$serviceManager->setService('Fucms\SiteConfig', $siteConfig);

$application->run();

//$finishTime = microtime();
//echo $finishTime - $time;
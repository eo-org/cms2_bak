<?php
chdir(dirname(__DIR__));

define("BASE_PATH", getenv('BASE_PATH'));

include BASE_PATH.'/inc/Zend/Loader/StandardAutoloader.php';

$autoLoader = new Zend\Loader\StandardAutoloader(array(
    'namespaces' => array(
        'Zend'	=> BASE_PATH.'/inc/Zend',
		'Core'	=> BASE_PATH.'/inc/Core',
		'Fucms' => BASE_PATH.'/library-cms/Fucms',
		'Brick' => BASE_PATH.'/extension/Brick'
    ),
    'prefixes' => array(
    	'App'	=> BASE_PATH.'/inc/App',
    	'Class' => BASE_PATH.'/library-cms/Class'
    )
));
$autoLoader->register();

$application = Zend\Mvc\Application::init(include 'config/application.config.php')->run();

$finishTime = microtime();
<?php
namespace Rest;

use Zend\EventManager\StaticEventManager;
use Zend\Mvc\MvcEvent;
use Zend\Serializer\Adapter\Json;

class Module
{
	public function init()
	{
		$sharedEvents = StaticEventManager::getInstance();
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'returnJson'), -10);
	}
	
	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
	}

	public function getConfig()
	{
		return include __DIR__ . '/configs/module.config.php';
	}
	
	public function returnJson(MvcEvent $e)
	{
		$jsonArr = $e->getResult();
		
		$response = $e->getResponse();
		$response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
		$adapter = new Json();
		$response->setContent($adapter->serialize($jsonArr));
		return $response;
		
	}
}
<?php
namespace Rest;

use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleEvent;
use Zend\EventManager\StaticEventManager;
use Zend\Session\Container;
use Core\Session\SsoAuth;
use Fucms\Session\Admin;
use Fucms\Brick\Register;
use Fucms\Brick\Service\RegisterConfigAdmin;

class Module
{
	public function init($moduleManager)
	{
		$sharedEvents = StaticEventManager::getInstance();
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'userAuth'), 10);
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'returnJson'), -10);
	}
	
    public function getConfig()
    {
    	return include __DIR__ . '/config/module.config.php';
    }
    
	public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
				)
            ),
        );
    }
    
	public function userAuth($e)
	{
		$fsa = new Admin();
		$ssoAuth = new SsoAuth($fsa);
		$ssoAuth->auth();
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
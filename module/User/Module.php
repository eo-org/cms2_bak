<?php
namespace User;

use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleEvent;
use Zend\EventManager\StaticEventManager;
use Zend\Session\Container;
use Core\Session\SsoAuth;
use Fucms\Session\Admin;
use Fucms\Brick\Register;
use Fucms\Brick\Service\RegisterConfig;

class Module
{
	public function init($moduleManager)
	{
		$sharedEvents = StaticEventManager::getInstance();
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'userAuth'), 1);
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'setLayout'), 10);
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
		/*
		$fsa = new Admin();
		$ssoAuth = new SsoAuth($fsa);
		$ssoAuth->auth();
		*/
	}
	
	public function setLayout(MvcEvent $e)
	{
		$controller = $e->getTarget();
		$sm = $controller->getServiceLocator();
		$factory = $controller->dbFactory();
		$rm = $e->getRouteMatch();
		
		$layoutFront = $sm->get('Fucms\Layout\Front');
		$layoutFront->setRouteMatch($rm);
		$layoutDoc = $layoutFront->getLayoutDoc();
		
		$brickRegister = new Register($this, new RegisterConfig($layoutDoc, $factory));
		
		$jsList = $brickRegister->getJsList();
		$cssList = $brickRegister->getCssList();
		
		$sessionAdmin = new Admin();
		
		$viewModel = $controller->layout();
		$viewModel->setVariables(array(
			'sessionAdmin' => $sessionAdmin,
			'factory' => $factory,
			'layoutFront' => $layoutFront,
			'jsList' => $jsList,
			'cssList' => $cssList,
		));
	}
}
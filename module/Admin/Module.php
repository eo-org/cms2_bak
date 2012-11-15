<?php
namespace Admin;
//use Zend\EventManager\EventInterface as Event;
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
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'setLayout'), -10);
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
				),
				'prefixes' => array(
					'Twig' => realpath('include/Twig')
				)
            ),
        );
    }
    
	public function userAuth(MvcEvent $e)
	{
		$controller = $e->getTarget();
		$orgCode = $controller->siteConfig('organizationCode');
		
		$fsa = new Admin();
		$fsa->setOrgCode($orgCode);
		$ssoAuth = new SsoAuth($fsa);
		$ssoAuth->auth();
	}
	
	public function setLayout(MvcEvent $e)
	{
		$controller = $e->getTarget();
		$controllerName = $controller->params()->fromRoute('controller');
		if(substr($controllerName, -5) == '.ajax') {
			$controller->layout('layout/layout.ajax.phtml');
		} else {
			$controller->layout('layout/layout.phtml');
		}
		
		$routeMatch = $e->getRouteMatch();
		$brickRegister = new Register($controller, new RegisterConfigAdmin());
		$jsList = $brickRegister->getJsList();
		$cssList = $brickRegister->getCssList();
		$brickViewList = $brickRegister->renderAll();
		
		$viewModel = $e->getViewModel();
		$viewModel->setVariables(array(
				'routeMatch'	=> $routeMatch,
				'brickViewList'	=> $brickViewList,
				'jsList'		=> $jsList,
				'cssList'		=> $cssList
		));
	}
}
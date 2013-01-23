<?php
namespace User;

use Zend\Mvc\MvcEvent, Zend\EventManager\StaticEventManager;
use Zend\View\Helper\Doctype, Zend\View\Helper\HeadTitle, Zend\View\Helper\HeadMeta;
use Fucms\Brick\Register, Fucms\Brick\Service\RegisterConfig, Fucms\Brick\Service\RegisterConfigAdmin;

class Module
{
	public function init($moduleManager)
	{
		$sharedEvents = StaticEventManager::getInstance();
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'userAuth'), 100);
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'initLayout'), -100);
		$sharedEvents->attach('UserAdmin', 'dispatch', array($this, 'initAdminLayout'), -100);
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
					'User'		=> __DIR__ . '/src/User',
					'UserAdmin'	=> __DIR__ . '/src/UserAdmin',
					'UserRest'	=> __DIR__ . '/src/UserRest'
				)
            ),
        );
    }
    
    public function getServiceConfig()
    {
    	return array(
    		'invokables' => array(
    			'Fucms\Session\User' => 'Fucms\Session\User',
    		)
    	);
    }
    
	public function userAuth(MvcEvent $e)
	{
		$sm = $e->getApplication()->getServiceManager();
		$fsu = $sm->get('Fucms\Session\User');
		if(!$fsu->isLogin()) {
			$rm = $e->getRouteMatch();
			$currentAction = $rm->getParam('action');
			if(!in_array($currentAction, array('login', 'register', 'forgetPassword'))) {
				$rm->setParam('action', 'login');
			}
		} else {
			$rm = $e->getRouteMatch();
			$currentAction = $rm->getParam('action');
			if(in_array($currentAction, array('login', 'register', 'forgetPassword'))) {
				$rm->setParam('action', 'index');
			}
		}
	}
	
	public function initAdminLayout(MvcEvent $e)
	{
		$controller = $e->getTarget();
		$controllerName = $controller->params()->fromRoute('controller');
	
		$controller->layout('layout-admin/layout');
	
		$routeMatch = $e->getRouteMatch();
		$brickRegister = new Register($controller, new RegisterConfigAdmin());
		$jsList = $brickRegister->getJsList();
		$cssList = $brickRegister->getCssList();
		$brickViewList = $brickRegister->renderAll();
	
		$config = $e->getApplication()->getServiceManager()->get('Config');
		$siteConfig = $e->getApplication()->getServiceManager()->get('ConfigObject\EnvironmentConfig');
		
		$viewModel = $e->getViewModel();
		$viewModel->setVariables(array(
			'routeMatch'	=> $routeMatch,
			'brickViewList'	=> $brickViewList,
			'jsList'		=> $jsList,
			'cssList'		=> $cssList,
			'toolbar'		=> $config['admin_toolbar'],
			'remoteSiteId'	=> $siteConfig->remoteSiteId,
		));
	}
	
	public function initLayout(MvcEvent $e)
	{
		$sm = $e->getApplication()->getServiceManager();
		$layoutFront = $sm->get('Fucms\Layout\Front');
		$dbFactory = $sm->get('Core\Mongo\Factory');
		
		$context = new Context($dbFactory);
		$context->init();
		$layoutFront->setContext($context);
		
		$layoutFront->initLayout($e);
	}
}
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
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'initLayout'), -100);
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
	
	public function initLayout(MvcEvent $e)
	{
		$sm = $e->getApplication()->getServiceManager();
		$layoutFront = $sm->get('Cms\Layout\Front');
		$dbFactory = $sm->get('Core\Mongo\Factory');
		
		$context = new Context($dbFactory);
		$context->init();
		$layoutFront->setContext($context);
		
		$layoutFront->initLayout($e);
	}
}
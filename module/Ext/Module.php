<?php
namespace Ext;

use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\StaticEventManager;

class Module
{
	public function init($moduleManager)
	{
		$sharedEvents = StaticEventManager::getInstance();
		$sharedEvents->attach('Application', 'dispatch', array($this, 'initTwig'), 15);
	}
	
	public function initTwig(MvcEvent $event)
	{
		$application = $event->getApplication();
		$sm = $application->getServiceManager();
		$config = $sm->get('Config');
		
		$twigEnv = Twig\View::getTwigEnv();
		$twigEnv->addFilter('outputImage',		new \Twig_Filter_Function('Brick\Helper\Twig\Filter::outputImage'));
		$twigEnv->addFilter('graphicDataJson',	new \Twig_Filter_Function('Brick\Helper\Twig\Filter::graphicDataJson'));
		$twigEnv->addFilter('substr',			new \Twig_Filter_Function('Brick\Helper\Twig\Filter::substr'));
		$twigEnv->addFilter('url',				new \Twig_Filter_Function('Brick\Helper\Twig\Filter::url'));
		$twigEnv->addFilter('pageLink',			new \Twig_Filter_Function('Brick\Helper\Twig\Filter::pageLink'));
		$twigEnv->addFilter('translate',		new \Twig_Filter_Function('Brick\Helper\Twig\Filter::translate'));
		
		$templateDir = array();
		$brickPathStackConfig = $config['brick_path_stack'];
		$fileLoader = new \Twig_Loader_Filesystem($brickPathStackConfig);
		Twig\View::setFileLoader($fileLoader);
		
		$dm = $sm->get('DocumentManager');
		$loader = new Twig\DatabaseLoader($dm, $fileLoader);
		
		$twigEnv->setLoader($loader);
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
					__NAMESPACE__ => __DIR__ . '/src/Ext',
				)
			)
		);
	}
}

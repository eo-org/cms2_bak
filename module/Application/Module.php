<?php
namespace Application;

use Zend\I18n\Translator\Translator;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Application;
use Zend\EventManager\StaticEventManager;

class Module
{
	public function init($moduleManager)
	{
		$listener = new \Cms\Cache\Listener\CacheListener();
		$sharedEvents = StaticEventManager::getInstance();
		$sharedEvents->attach('Zend\Mvc\Application', $listener, null);
		$sharedEvents->attach('Zend\Mvc\Application', 'dispatch.error', array($this, 'onError'), 100);
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'setTranslator'), 11);
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
					__NAMESPACE__ => __DIR__ . '/src/Application',
				)
			)
		);
	}
	
	public function setTranslator(MvcEvent $e)
	{
		$controller = $e->getTarget();
		$sm = $controller->getServiceLocator();
		
		$factory = $controller->dbFactory();
		$co = $factory->_m('Info');
		$infoDoc = $co->fetchOne();
		
		$locale = 'zh_CN';
		if(!is_null($infoDoc)) {
			$locale = $infoDoc->language;
		}
		$translator = Translator::factory(array(
			'locale' => $locale,
			'translation_file_patterns' => array(
				array(
					'type'			=> 'gettext',
					'base_dir'		=> __DIR__ . '/language',
					'pattern'		=> '%s.mo',
				)
			)
		));
		$sm->setService('translator', $translator);
	}
	
	public function initLayout(MvcEvent $e)
	{
		$layoutFront = $e->getApplication()->getServiceManager()->get('Fucms\Layout\Front');
		$layoutFront->initLayout($e);
	}
	
	public function onError(MvcEvent $e)
	{
		$target = $e->getTarget();
		if($target instanceof Application) {
			echo "handled in onError Event<br />";
			echo $e->getError();
			echo "<br />";
			die('END');
		} else {
			$target->layout('layout/error');
		}
	}
}

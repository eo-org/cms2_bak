<?php
namespace Application;

use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;
use Zend\I18n\Translator\Translator;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Application;
use Zend\EventManager\StaticEventManager;
use Brick\Module\TwigView;
use Fucms\Session\Admin as SessionAdmin;

class Module implements BootstrapListenerInterface
{
	public $infoDoc = null;
	
	public function init($moduleManager)
	{
		$sharedEvents = StaticEventManager::getInstance();
		$sharedEvents->attach('Zend\Mvc\Application', 'dispatch.error', array($this, 'onError'), 100);
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'setTranslator'), 11);
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'initLayout'), -100);
//		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'setHeadFile'), -1);
	}
	
	public function onBootstrap(EventInterface $event)
	{
		$application = $event->getTarget();
		$sm = $application->getServiceManager();
		$config = $sm->get('Config');
		$brickConfig = $config['brick'];
		
		$twigEnv = TwigView::getTwigEnv();
		$twigEnv->addFilter('outputImage',		new \Twig_Filter_Function('Brick\Helper\Twig\Filter::outputImage'));
		$twigEnv->addFilter('graphicDataJson',	new \Twig_Filter_Function('Brick\Helper\Twig\Filter::graphicDataJson'));
		$twigEnv->addFilter('substr',			new \Twig_Filter_Function('Brick\Helper\Twig\Filter::substr'));
		$twigEnv->addFilter('url',				new \Twig_Filter_Function('Brick\Helper\Twig\Filter::url'));
		$twigEnv->addFilter('pageLink',			new \Twig_Filter_Function('Brick\Helper\Twig\Filter::pageLink'));
		$twigEnv->addFilter('translate',		new \Twig_Filter_Function('Brick\Helper\Twig\Filter::translate'));
		
		$templateDir = array();
		foreach($brickConfig as $bc) {
			$templateDir[] = $bc['path_stack'];
		}
		$loader = new \Twig_Loader_Filesystem($templateDir);
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
					__NAMESPACE__ => __DIR__ . '/src/Application',
				)
			)
		);
	}
	
	public function getServiceConfig()
	{
		return array(
			'invokables' => array(
				'Fucms\Layout\Front' => 'Fucms\Layout\Front',
				'Fucms\Session\Admin' => 'Fucms\Session\Admin',
			)
		);
	}
	
	public function setTranslator(MvcEvent $e)
	{
		$controller = $e->getTarget();
		$sm = $controller->getServiceLocator();
		
		$factory = $controller->dbFactory();
		$co = $factory->_m('Info');
		$this->infoDoc = $co->fetchOne();
		
		$locale = 'zh_CN';
		if(!is_null($this->infoDoc)) {
			$locale = $this->infoDoc->language;
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

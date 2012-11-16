<?php
namespace Application;

use Zend\I18n\Translator\Translator;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Application;
use Zend\View\Helper\Doctype;
use Zend\View\Helper\HeadTitle;
use Zend\View\Helper\HeadMeta;
use Zend\EventManager\StaticEventManager;
use Fucms\Brick\Register;
use Fucms\Brick\Service\RegisterConfig;
use Fucms\Session\Admin as SessionAdmin;

class Module
{
	public $infoDoc = null;
	
	public function init($moduleManager)
	{
		$sharedEvents = StaticEventManager::getInstance();
		$sharedEvents->attach('Zend\Mvc\Application', 'dispatch.error', array($this, 'onError'), 100);
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'setTranslator'), 11);
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'setLayout'), 10);
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'setHeadFile'), -1);
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
				),
				'prefixes' => array(
					'Twig' => realpath('include/Twig')
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
	
	public function setLayout(MvcEvent $e)
	{
		$controller = $e->getTarget();
		$sm = $controller->getServiceLocator();
		$factory = $controller->dbFactory();
		$rm = $e->getRouteMatch();
		
		$infoDoc = $this->infoDoc;
		$controller->setSiteDoc($infoDoc);
		$doctypeHelper = new Doctype();
		$doctypeHelper->setDoctype('HTML5');
		if(!is_null($infoDoc)) {
			$renderer = $sm->get('Zend\View\Renderer\PhpRenderer');
    		$renderer->headTitle($infoDoc->pageTitle);
			$renderer->headMeta()->setName('keywords', $infoDoc->metakey);
			$renderer->headMeta()->setName('description', $infoDoc->metadesc);
		}
		
		$layoutFront = $sm->get('Fucms\Layout\Front');
		$layoutFront->setRouteMatch($rm);
		$layoutDoc = $layoutFront->getLayoutDoc();
		
		$brickRegister = new Register($controller, new RegisterConfig($layoutDoc, $factory));
		$sm->setService('Brick\Register', $brickRegister);
		$controller->setBrickRegister($brickRegister);
		
		$sessionAdmin	= new SessionAdmin();
		$viewModel		= $controller->layout();
		$jsList			= $brickRegister->getJsList();
		$cssList		= $brickRegister->getCssList();
		
		$viewModel->setVariables(array(
			'factory' => $factory,
			'sessionAdmin' => $sessionAdmin,
			'layoutFront' => $layoutFront,
			'jsList' => $jsList,
			'cssList' => $cssList,
		));
	}
	
	public function setHeadFile(MvcEvent $e)
	{
		$controller = $e->getTarget();
		$sm = $controller->getServiceLocator();
		$factory = $controller->dbFactory();
		
		$siteConfig = $sm->get('Fucms\SiteConfig');
		$viewHelper = $sm->get('ViewHelperManager');
		$headFileCo = $factory->_m('HeadFile');
		$headFileDocs = $headFileCo->fetchDoc();
		
		$sessionAdmin = new SessionAdmin();
		$fileUrl = $siteConfig->fileFolderUrl;
		if($sessionAdmin->getUserData('localCssMode') == 'active') {
			$fileUrl = 'http://local.host/'.$siteConfig->globalSiteId;
		}
		
		foreach($headFileDocs as $doc) {
			if($doc->folder == 'helper') {
				$viewHelper->get('HeadScript')->appendFile($siteConfig->libUrl.'/front/script/helper/'.$doc->filename);
			} else {
				if($doc->type == 'css') {
					$viewHelper->get('HeadLink')->appendStylesheet($fileUrl.'/'.$doc->filename);
				} else {
					$viewHelper->get('HeadScript')->appendFile($fileUrl.'/'.$doc->filename);
				}
			}
		}
	}
	
	public function onError(MvcEvent $e)
	{
		$target = $e->getTarget();
		if($target instanceof Application) {
			echo "handled in onError Event<br />";
			echo $e->getError();
			die('END');
		} else {
			$target->layout('layout/error');
		}
	}
}

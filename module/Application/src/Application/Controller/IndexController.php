<?php
namespace Application\Controller;

use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
	public $brickRegister = null;
	public $siteDoc = null;
	
    public function indexAction()
    {
    	$sm = $this->getServiceLocator();
    	$lf = $sm->get('Fucms\Layout\Front');
    	$layoutDoc	= $lf->getLayoutDoc();
    	$brickRegister = $sm->get('Brick\Register');
    	
    	$brickViewList = $brickRegister->renderAll();
    	$stageList = $layoutDoc->stage;
    	
        return array(
        	'hideHead' => $layoutDoc->hideHead,
        	'hideTail' => $layoutDoc->hideTail,
        	'layoutId' => $layoutDoc->getId(),
        	'stageList' => $stageList,
        	'brickViewList' => $brickViewList
        );
    	
    	
		
    /*
        
        $controller->registerPlugin(new App_Plugin_BackendSsoAuth(
        	$csa,
        	App_Plugin_BackendSsoAuth::CMS,
        	Class_Server::API_KEY
        ));
        $controller->registerPlugin(new Class_Plugin_HeadFile());
        $controller->registerPlugin(new Class_Plugin_LayoutSwitch($layout));
//        $controller->registerPlugin(new Class_Plugin_BrickRegister($layout));
        
		$view = new Zend_View();
		$view->headTitle()->setSeparator('_');
		
		Zend_Registry::set('Locale', 'zh_CN');
		$co = App_Factory::_m('Info');
		$doc = $co->fetchOne();
		if(!is_null($doc)) {
			$view->headTitle($doc->pageTitle);
			$view->headMeta()->appendName('keywords', $doc->metakey);
			$view->headMeta()->appendName('description', $doc->metadesc);
			if(!is_null($doc->language)) {
				Zend_Registry::set('Locale', $doc->language);
			}
		}
		
		
		
		
		
		*/
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	//$mongoAdapter = $sm->get('Core\Mongo\Db\Adapter');
    	
    	//\App_Mongo_Db_Collection::setDefaultAdapter($mongoAdapter);
    	
    	
    	
    	
    }
    
    public function setBrickRegister($br)
    {
    	$this->brickRegister = $br;
    }
    
    public function getBrickRegister()
    {
    	return $this->brickRegister;
    }
    
    public function setSiteDoc($siteDoc)
    {
    	$this->siteDoc = $siteDoc;
    }
    
    public function getSiteDoc()
    {
    	return $this->siteDoc;
    }
}

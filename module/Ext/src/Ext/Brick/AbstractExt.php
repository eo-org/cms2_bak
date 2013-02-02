<?php
namespace Ext\Brick;

use Exception;
use Ext\Twig\View;

abstract class AbstractExt
{
	protected $_brick = null;
	
	protected $_params = null;
	
	protected $controller;
	
	protected $sm;
	
    protected $_disableRender = false;
    
    protected $_effectFiles = null;
    
    protected $view = null;
    
    public function initParam($brick, $controller)
    {
    	$this->_brick = $brick;
    	$this->_params = (object)$brick->params;
    	$this->controller = $controller;
    	$this->sm = $controller->getServiceLocator();
    }
    
    abstract public function getTplList();
    
    public function getFormClass()
    {
    	return null;
    }
	
    public function getCacheId()
    {
    	return null;
    }
    
    public function dbFactory()
    {
    	return $this->sm->get('Core\Mongo\Factory');
    }
    
    public function getController()
    {
    	return $this->controller;
    }
    
	public function configParam($form)
    {
    	$formClassName = $this->getFormClass();
    	
    	if(!is_null($formClassName)) {
	    	$paramForm = new $formClassName($this->dbFactory());
	    	$form->add($paramForm);
    	}
    	return $form;
    }
    
    public function getExtName()
    {
    	return $this->_brick->extName;
    }
    
	public function getBrickId()
    {
    	return $this->_brick->brickId;
    }
    
    public function getBrickName()
    {
    	return $this->_brick->brickName;
    }
    
    public function getPosition()
    {
    	return $this->_brick->position;
    }
    
    public function getSpriteName()
    {
    	return $this->_brick->spriteName;
    }
    
    public function getEffectFiles()
    {
    	return $this->_effectFiles;
    }
    
	public function getParam($key, $defaultValue = NULL)
    {
    	$params = $this->_params;
    	if(isset($params->$key)) {
    		$temp = $params->$key;
    		return $temp;
    	}
    	return $defaultValue;
    }
    
    public function setParam($key, $value)
    {
    	$this->_params->$key = $value;
    	return true;
    }
    
    public function setParams($src, $type = 'array')
    {
    	if(!empty($src)) {
	    	if($type == 'json') {
	    		$src = Zend_Json_Decoder::decode($src);
	    	}
	    	foreach($src as $key => $value) {
	    		if(!empty($value)) {
	    			$this->_params->$key = $value;
	    		}
	    	}
    	}
    }
    
//     public function setScriptFile($filename)
//     {
//     	$this->_scriptName = $filename;
//     }
    
//     public function path()
//     {
//     	$extName = strtolower($this->_brick->extName);
//         $path = str_replace('_', '/', $extName);
//         return '/'.$path;
//     }
    
//     public function twigPath()
//     {
//     	$sm = $this->sm;
//     	$siteConfig = $sm->get('ConfigObject\EnvironmentConfig');
//     	$globalSiteId = $siteConfig->globalSiteId;
//     	$twigPath = BASE_PATH.'/tpl/'.$globalSiteId.'/'.$this->_brick->extName;
//         return $twigPath;
//     }
    
    public function render($type = null)
    {
    	if($this->_disableRender === true) {
	        return "<div class='no-render'></div>";
    	} else if(is_string($this->_disableRender)) {
    		return "<div class='".$this->_disableRender."' brickId='".$this->_brick->getId()."'>无法找到对应的URL，此模块内容为空</div>";
    	} else {
    		$tplName = $this->_brick->tplName;
    		$systemTplList = $this->getTplList();
	    	$this->view = new View();
	    	
// 	    	if(!array_key_exists($tplName, $systemTplList)) {
// 	    		echo $systemTplList[$tplName];
// 				//$this->view->setScriptPath(BASE_PATH.'/extension/view'.$this->path());
// 	    	} else {
// 				//$this->view->setScriptPath($this->twigPath());
// 	    	}
			
			$this->view->assign($this->_params);
			$this->prepare();
			
			$this->view->setBrickId($this->_brick->getId())
				->setExtName($this->_brick->extName)
				->setClassSuffix($this->_brick->cssSuffix);
			
			$this->view->brickName = $this->_brick->brickName;
			$this->view->brickId = $this->_brick->getId();
			$this->view->displayBrickName = $this->_brick->displayBrickName;
			
			try {
				if(isset($systemTplList[$tplName])) {
					return $this->view->render($systemTplList[$tplName]);
				} else {
					return $this->view->render($tplName);
				}
			} catch(Exception $e) {
				return $e->getMessage()." critical error within brick id: ".$this->_brick->getId().'!!<br /><a href="#/admin/brick.ajax/edit/brick-id/'.$this->_brick->getId().'">reset parameters</a>';
			}
    	}
    }
    
//     public function configTplOptions($form)
//     {
//     	$tplArray = $this->getTplList();
    	
//     	$userFolder = TEMPLATE_PATH.$this->twigPath();
//     	$userTplArray = array();
// 		if(is_dir($userFolder)) {
// 			$handle = opendir($userFolder);
// 	    	while($file = readdir($handle)) {
// 	    		if(strpos($file, '.tpl')) {
// 	    			$userTplArray[$file] = $file;
// 	    		}
// 	    	}
// 		}
// 		if(count($userTplArray) > 0) {
// 			$tplArray = array('system' => $tplArray, 'user' => $userTplArray);
// 		}
    	
//     	$form->tplName->setMultiOptions($tplArray);
//     	return $form;
//     }
    
    public function getTplArray()
    {
    	$sysTplArray = $this->getTplList();
		
		$tplArray = array(
			array('label' => 'system', 'options' => $sysTplArray,),
			array('label' => 'user', 'options' => array())
		);
    	return $tplArray;
    }
}
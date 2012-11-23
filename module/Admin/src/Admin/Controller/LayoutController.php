<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Admin\Form\Layout\CreateForm;
use Admin\Form\Layout\EditForm;
use Admin\Form\Layout\EditDefaultForm;
use Admin\Form\Layout\EditStageForm;

class LayoutController extends AbstractActionController
{
    public function indexAction()
    {
        $labels = array(
            'label' => '页面名',
        	'controllerName' => 'Folder Url',
        	'~contextMenu' => ''
        );
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header-front.phtml', array(
            'labels' => $labels,
            'url' => '/admin/layout/get-layout-json/',
            'actionId' => 'id',
        	'click' => array(
            	'action' => 'contextMenu',
            	'menuItems' => array(
            		array('编辑', '/admin/layout/edit/id/')
            	)
            ),
            'initSelectRun' => 'true',
            'hashParam' => $hashParam
        ));

        $this->view->assign('partialHTML', $partialHTML);
        $this->_helper->template->actionMenu(array('create'));
    }
    
    public function createAction()
    {
        $form = new CreateForm();
        
        if($this->getRequest()->isPost()) {
        	$postData = $this->getRequest()->getPost();
        	$form->setData($postData);
        	if($form->isValid()) {
	        	$factory = $this->dbFactory();
	        	$co = $factory->_m('Layout');
	        	$doc = $co->create();
	        	$doc->setFromArray($form->getData());
	        	$doc->default = 0;
	        	$doc->save();
        	
        		return $this->switchContext('layout');
        	}
        }
        
        $this->brickConfig()->setActionMenu(array('create-save'))
			->setActionTitle('创建新页面布局');
        
        return array(
			'form' => $form
		);
    }
    
    public function editAction()
    {
        $layoutId = $this->params()->fromRoute('id');
        $factory = $this->dbFactory();
        $layoutDoc = $factory->_m('Layout')->find($layoutId);
        
    	if($layoutDoc->default == 1) {
    		$form = new EditDefaultForm();
        	$this->brickConfig()->setActionMenu(array('save'));
        } else {
        	$form = new EditForm();
			$this->brickConfig()->setActionMenu(array('save', 'delete'));
        }
        $this->brickConfig()->setActionTitle('编辑页面布局');
        
        $form->setData($layoutDoc->toArray());
        if($this->getRequest()->isPost()) {
        	$postData = $this->getRequest()->getPost();
        	$form->setData($postData);
        	if($form->isValid()) {
	        	$layoutDoc->setFromArray($form->getData());
				$layoutDoc->save();
				return $this->switchContext('layout');
        	}
        }
        
        return array('form' => $form);
    }
    
    public function editStageAction()
    {
    	$stageId = $this->params()->fromRoute('stageId');
    	$factory = $this->dbFactory();
    	$co = $factory->_m('Layout');
    	$doc = $co->addFilter('stage.stageId', $stageId)->fetchOne();
    	
    	$stages = $doc->stage;
    	$tempKey = null;
    	foreach($stages as $key => $val) {
    		if($val['stageId'] == $stageId) {
    			$tempKey = $key;
    			break;
    		}
    	}
    	
        $form = new EditStageForm();
        $form->setData($stages[$tempKey]);
    	if($this->getRequest()->isPost()) {
    		$postData = $this->getRequest()->getPost();
        	$form->setData($postData);
        	
        	if($form->isValid()) {
	        	$stages[$tempKey]['uniqueId'] = $form->get('uniqueId')->getValue();
				$doc->stage = $stages;
	        	$doc->save();
	        	
	            return $this->switchContext('layout');
    		}
        }
        
        $this->brickConfig()->setActionMenu(array('save'))
        	->setActionTitle('设置 Stage Id');
        
        return array('form' => $form);
    }
    
    public function setPageAction()
    {
    	
    }
    
    public function deleteAction()
    {
    	
    }
    
    public function getLayoutJsonAction()
    {
        $pageSize = 30;
        
	    $tb = Class_Base::_('Layout');
	    $selector = $tb->select()->limitPage(1, $pageSize);
		
        $result = array();
        
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
            		case 'page':
            		    $selector->limitPage(intval($value), $pageSize);
                        $result['currentPage'] = intval($value);
            		    break;
                }
            }
        }
        $rowset = $tb->fetchAll($selector)->toArray();
        $result['data'] = $rowset;
        $result['dataSize'] = Class_Func::count($selector);
        $result['pageSize'] = $pageSize;
        
        if(!isset($result['currentPage'])) {
        	$result['currentPage'] = 1;
        }
        
        return $this->_helper->json($result);
    }
}
<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Admin\Form\Product\EditForm;

class ProductController extends AbstractActionController
{
    public function indexAction()
    {
		$this->brickConfig()->setActionMenu(array('create'))
			->setActionTitle('产品列表');
		
		$factory = $this->dbFactory();
		$groupDoc = $factory->_m('Group')->findProductGroup();
    	$optVal = $groupDoc->toMultioptions('label');
		
		return array(
			'optVal' => $optVal
		);
    }
	
	public function createAction()
	{
		$factory = $this->dbFactory();
		$attributesetCo = $factory->_m('Attributeset');
		
		$attrDocSet = $attributesetCo->setFields(array('label'))
			->addFilter('type', 'product')
			->fetchAll();
		
		$this->brickConfig()->setActionMenu(array())
			->setActionTitle('选择产品类型');
		
		return array(
			'attrRowset' => $attrDocSet
		);
	}
	
	public function editAction()
	{
		$id = $this->params()->fromRoute('id');
		$attributesetId = $this->params()->fromRoute('attributeset-id');
		$form = new EditForm();
		
		$factory = $this->dbFactory();
		$groupDoc = $factory->_m('Group')->addFilter('type', 'product')
    		->fetchOne();
    	$items = $groupDoc->toMultioptions('label');
    	$form->get('groupId')->setValueOptions($items);
    	
		$co = $factory->_m('Product');
        $doc = null;
        if(empty($id)) {
            $doc = $co->create();
            $this->brickConfig()->setActionTitle('新建产品')
        		->setActionMenu(array('save'));
        } else {
        	$doc = $co->find($id);
        	$this->brickConfig()->setActionTitle('编辑产品: '.$doc->label)
        		->setActionMenu(array('save', 'delete'));
        }
        if(is_null($doc)) {
            throw new Class_Exception_AccessDeny('没有权限访问此内容，或者产品id不存在');
        }
		
		$attributesetDoc = $doc->getAttributesetDoc();
		if(!is_null($attributesetDoc)) {
			$attrElements = $attributesetDoc->getZfElements();
		} else {
			$attrElements = array();
		}
		
		//$form->addElements($attrElements);
		$form->setData($doc->toArray());
		
		if($this->getRequest()->isPost()) {
			$postData = $this->getRequest()->getPost();
        	$form->setData($postData);
        	if($form->isValid()) {
	        	$doc->setFromArray($form->getData());
	    		$attaUrl	= $postData->get('attaUrl');
				$attaName	= $postData->get('attaName');
				$attaType	= $postData->get('attaType');
				$doc->setAttachments($attaUrl, $attaName, $attaType);
	            $doc->save();
	            $this->flashMessenger()->addMessage('产品:'.$doc->label.' 已经成功保存');
	            return $this->redirect()->toRoute(null, array('action' => 'index', 'controller' => 'product'));
        	}
		}
		
		$co = $factory::_m('Info');
		$infoDoc = $co->fetchOne();
		
		$thumbWidth = empty($infoDoc->thumbWidth) ? 200 : $infoDoc->thumbWidth;
		$thumbHeight = empty($infoDoc->thumbHeight) ? 200 : $infoDoc->thumbHeight;
		$siteId = $this->siteConfig('extUrl');
		$time = time();
		$fileServerKey = 'gioqnfieowhczt7vt87qhitonqfn8eaw9y8s90a6fnvuzioguifeb';
		$sig = md5($siteId.$time.$fileServerKey);
        
		return array(
			'form'			=> $form,
			'product'		=> $doc,
			'thumbWidth'	=> $thumbWidth,
			'thumbHeight'	=> $thumbHeight,
			'time'			=> $time,
			'sig'			=> $sig
		);
	}
	
	public function deleteAction()
	{
		$id = $this->params()->fromRoute('id');
		
		$productCo = $factory->_m('Product');
		$productDoc = $productCo->find($id);
		
		if($productDoc == null){
			throw new Class_Exception_Pagemissing();
		}
		$productName = $productDoc->label;
		$productDoc->delete();
		$this->_helper->flashMessenger->addMessage('产品 '.$productName.' 已经删除');
		$this->_helper->switchContent->gotoSimple('index');
	}
}
<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Admin\Form\ProductType\EditForm; 

class ProductTypeController extends AbstractActionController
{
	public function indexAction()
	{
		$this->brickConfig()->setActionMenu(array('create-edit'))
			->setActionTitle('产品类型');
	}
	
	public function editAction()
	{
		$id = $this->params()->fromRoute('id');
		$factory = $this->dbFactory();
		$attributesetCo = $factory->_m('Attributeset');
		if(empty($id)) {
			$doc = $attributesetCo->create();
		} else {
			$doc = $attributesetCo->find($id);
		}
		
		$form = new EditForm();
		$form->setData($doc->toArray());
		if($this->getRequest()->isPost()) {
			$postData = $this->getRequest()->getPost();
        	$form->setData($postData);
        	if($form->isValid()) {
				$doc->setFromArray($form->getData());
				$doc->save();
				$this->flashMessenger()->addMessage('产品类型:'.$doc->label.' 已经成功保存');
	            return $this->redirect()->toRoute('admin/actionroutes/wildcard', array('action' => 'index', 'controller' => 'product-type'));
        	}
		}
		
		$this->brickConfig()->setActionMenu(array('save'))
			->setActionTitle('编辑产品类型');
		
		return array('form' => $form);
	}
	
	public function deleteAction()
	{
		
	}
}
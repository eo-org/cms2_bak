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
}
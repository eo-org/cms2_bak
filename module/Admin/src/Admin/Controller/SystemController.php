<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Admin\Form\Article\EditForm;

class SystemController extends AbstractActionController 
{
	public function indexAction()
	{
		$this->brickConfig()->setActionMenu(array())
			->setActionTitle('系统管理');
	}
	
	public function headFileAction()
	{
		$this->brickConfig()->setActionMenu(array())
			->setActionTitle('CSS & JS Files');
		
		$factory = $this->dbFactory();
		$docs = $factory->_m('HeadFile')->fetchDoc();
		
		return array(
			'docs' => $docs
		);
	}
}
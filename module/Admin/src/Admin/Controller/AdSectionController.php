<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class AdSectionController extends AbstractActionController
{
	public function indexAction()
	{
		$this->brickConfig()->setActionMenu(array(
				array('label' => '添加新分组', 'callback' => '/rest/ad-section', 'method' => 'createAdSection')
			))->setActionTitle('广告分组');
	}
}
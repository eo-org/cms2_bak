<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class AdController extends AbstractActionController
{	
	public function indexAction()
	{
		$this->brickConfig()->setActionMenu(array(
				array('label' => '添加广告图', 'callback' => '/rest/ad', 'method' => 'createAd')
			))->setActionTitle('广告图');
		
		$sectionId = $this->params()->fromRoute('section-id');
		return array(
			'sectionId' => $sectionId,
		);
	}
}
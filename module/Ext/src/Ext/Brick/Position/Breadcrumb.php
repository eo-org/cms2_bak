<?php
namespace Ext\Brick\Position;

use Ext\Brick\AbstractExt;

class Breadcrumb extends AbstractExt
{
    public function prepare()
    {
    	$sm = $this->_controller->getServiceLocator();
    	$layoutFront = $sm->get('Fucms\Layout\Front');
    	$context = $layoutFront->getContext();
    	
		$breadcrumbArr = $context->getBreadcrumb();
		if($breadcrumbArr == null) {
			$this->_disableRender = 'no-resource';
			return false;
		}
		$this->view->breadcrumbArr = $breadcrumbArr;
    }
    
    public function getTplList()
    {
    	return array('view' => 'position\breadcrumb\view.tpl');
    }
}
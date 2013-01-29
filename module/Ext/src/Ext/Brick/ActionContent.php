<?php
namespace Ext\Brick;

use Ext\Brick\AbstractExt;

class ActionContent extends AbstractExt
{
	public function prepare()
	{
		$controller = $this->_controller;
		
		$content = $controller->layout()->content;
		
		$this->view->content = $content;
	}
	
	public function getTplList()
	{
		return array('view' => 'action-content/view.tpl');
	}
}
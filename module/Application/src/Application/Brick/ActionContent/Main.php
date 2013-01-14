<?php
namespace Application\Brick\ActionContent;

use Brick\Module\AbstractBrick;

class Main extends AbstractBrick
{
	public function prepare()
	{
		$controller = $this->_controller;
		
		$content = $controller->layout()->content;
		
		$this->view->content = $content;
	}
	
	public function getClass()
	{
		return null;
	}
	
	public function getTplList()
	{
		return array('view.tpl' => 'action-content/view.tpl');
	}
}
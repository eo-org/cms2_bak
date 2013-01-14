<?php
namespace User;

use Fucms\Layout\ContextAbstract;

class Context extends ContextAbstract
{
	protected $type;
	
	public function init($id = 'user')
	{
		$layoutCo = $this->dbFactory->_m('Layout');
		
		$layoutDoc = $layoutCo->addFilter('type', 'user')
			->addFilter('default', 1)
			->fetchOne();
		if($layoutDoc == null) {
			$layoutDoc = $this->createDefaultLayout('user');
		}
		
		$this->layoutDoc = $layoutDoc;
	}
	
	public function getBreadcrumb()
	{
		return null;
	}
	
	public function getResourceId()
	{
		return $this->layoutDoc->getId();
	}
	
	public function getTitle()
	{
		return $this->layoutDoc->label;
	}
	
	public function getType()
	{
		return "user";
	}
}
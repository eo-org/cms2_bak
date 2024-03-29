<?php
namespace Ext\Brick\Article;

use Ext\Brick\AbstractExt;

class ChildGroupIndex extends AbstractExt
{
	public function prepare()
	{
		$sm = $this->_controller->getServiceLocator();
		$layoutFront = $sm->get('Fucms\Layout\Front');
		$context = $layoutFront->getContext();
		
		$groupItemId = $context->getGroupItemId();
		$groupDoc = $context->getGroupDoc();
		
		$branchIndex = $groupDoc->getLeaf($groupItemId);
		$branchIndexArr = array($branchIndex);
		
		$this->view->branchIndexArr = $branchIndexArr;
		$this->view->currentGroupItemId = $groupItemId;
	}
	
	public function getTplList()
	{
		return array(
			'view' => 'article\childgroupindex\view.tpl'
		);
	}
}
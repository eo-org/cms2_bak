<?php
namespace UserAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class UserGroupController extends AbstractActionController
{
	public function indexAction()
	{
		$this->actionMenu = array('create');
		$this->actionTitle = '用户权限管理';
	}
	
	public function createAction()
	{
		$form = new FormEdit();
		
		$this->actionMenu = array('save');
		$this->actionTitle = '创建新用户组';
	}
		
	public function editAction()
	{
		$id = $this->params('id');
		
		$dm = $this->documentManager();
		$user = $dm->getRepository('User\Document\User')->findOneById($id);
		print_r($user->toArray());
		
		$this->actionMenu = array('save');
		$this->actionTitle = '保存用户信息';
	}
}

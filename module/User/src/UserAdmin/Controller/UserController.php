<?php
namespace UserAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class UserController extends AbstractActionController
{
	public function indexAction()
	{
		$this->actionMenu = array();
		$this->actionTitle = '用户管理';
	}
	
	public function createAction()
	{
		$id = $this->getRequest()->getParam('id');
		$orgCode = Class_Server::getOrgCode();
		$forumCo = App_Factory::_m('Forum');
		$forumDoc = $forumCo->addFilter("forumid", $orgCode)->fetchOne();
		$setrow = empty($forumDoc)?$forumDoc:$forumDoc->toArray();
		$postCo = App_Factory::_m('Post');
		$row = $postCo->addFilter("parentId", $id)->sort('_id',1)->fetchAll();
		foreach ($row as $num ){
			$viewrow[] = $num;
		}
		if(!empty($viewrow)){
			$csa = Class_Session_User::getInstance();
			$lastReplyUsername = $csa->getUserData('loginName');
			if($this->getRequest()->isPost()){
				//$lastReplyUsername = $this->getRequest()->getParam('lastReplyUsername');
				$lastReply = $this->getRequest()->getParam('lastReply');
				$datatime = date('Y-m-d H:i:s',time());
				$arrdata = array(
						'lastReplyUsername' => $lastReplyUsername,
						'lastReply' => $lastReply,
						'lastDatatime' => $datatime,
						'parentId' => $id
						);
				$postDoc = $postCo->find($id);
				$postDoc->setFromArray($arrdata);
				$postDoc->save();
				$postInDoc = $postCo->create();
				$i = end($viewrow);
				$arrdata['sort'] = $i['sort']+1;
				$postInDoc->setFromArray($arrdata);
				$postInDoc->save();
				$this->_helper->redirector()->gotoSimple('create');
			}
			$this->view->lastReplyUsername = $lastReplyUsername;
			$this->view->row = $viewrow;
			$this->view->id = $id;
		} else {
			$this->view->state = 1;
		}
		$this->_helper->template->actionMenu(array('delete'));
		$this->view->setrow = $setrow;
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

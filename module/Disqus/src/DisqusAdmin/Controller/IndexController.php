<?php
namespace DisqusAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
	public function indexAction()
	{
		$this->brickConfig()->setActionMenu(array())
			->setActionTitle('留言管理');
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
		$orgCode = Class_Server::getOrgCode();
		$forumCo = App_Factory::_m('Forum');
		$forumDoc = $forumCo->addFilter("forumid", $orgCode)->fetchOne();
		$this->view->setrow = empty($forumDoc)?$forumDoc:$forumDoc->toArray();
		$id = $this->getRequest()->getParam('id');
		$title = $this->getRequest()->getParam('title');
		$content = $this->getRequest()->getParam('content');
		$isshow = $this->getRequest()->getParam('isshow');
		$status = $this->getRequest()->getParam('status');
		$postCo = App_Factory::_m('Post');
		$postDoc = $postCo->find($id);
		if(!empty($title)){
			$arrup = array(
					'title' => $title,
					'content' => $content,
					'isShow' => $isshow,
					'status' => $status
				);
			$postDoc->setFromArray($arrup);
			$postDoc->save();
		}
		$postDoc = $postCo->find($id);
		$this->view->row= $postDoc->toArray();
		$post = $this->view->render('edit/editpost.phtml');
		echo $post;
		exit;
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$state = $this->getRequest()->getParam('state');
		$postCo = App_Factory::_m('Post');
		if($state == 2){
			$postDoc = $postCo->find($id);
			$postid = $postDoc->toArray();
			$postrow = $postCo->find($postid['parentId']);
			$row = $postrow->toArray();
			$postDoc->delete();
			if($postid['lastReply'] == $row['lastReply']){
				$forumDoc = $postCo->addFilter("parentId", $postid['parentId'])->sort('sort',-1)->fetchAll();
				foreach ($forumDoc as $num => $arrone){
					if($arrone['sort']==1){
						$arrup = array(
								'lastReplyUsername' => '',
								'lastReply' => '',
								'lastDatatime' => ''
						);
					}else{
						$arrup = array(
								'lastReplyUsername' => $arrone['lastReplyUsername'],
								'lastReply' => $arrone['lastReply'],
								'lastDatatime' => $arrone['lastDatatime']
								);
					}
					break;		
				}
				$postrow->setFromArray($arrup);
				$postrow->save();
				exit;
			}
			
		} else {
			$postDoc = $postCo->addFilter("parentId", $id)->fetchAll();
			foreach ($postDoc as $num){
				$optionDoc = $postCo->find($num['_id']);
				$optionDoc->delete();
			}
			$this->_redirect('/'.Class_Server::getOrgCode().'/admin/index/index/');
		}
		exit;
	}
	
	public function getFormJsonAction()
	{
		$pageSize = 20;
		$postCo = App_Factory::_m('Post');
		$postCo->addFilter("orgCode", Class_Server::getOrgCode());
		$postCo->sort('_id', -1);
		$result = array();
		foreach($this->getRequest()->getParams() as $key => $value) {
			if(substr($key, 0 , 7) == 'filter_') {
				$field = substr($key, 7);
				switch($field) {
					case 'type':
						$postCo->addFilter('formName', new MongoRegex("/^".$value."/"));
						break;
					case 'page':
						if(intval($value) != 0) {
    						$currentPage = $value;
    					}
    					$result['currentPage'] = intval($value);
						break;
				}
			}
		}
		$data = $postCo->fetchAll(true);
		$dataSize = $postCo->count();
		$result['data'] = $data;
		$result['dataSize'] = $dataSize;
		$result['pageSize'] = $pageSize;
		$result['currentPage'] = $currentPage;
		
		return $this->_helper->json($result);
	}
}

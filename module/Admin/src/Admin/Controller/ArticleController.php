<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Admin\Form\Article\EditForm;

class ArticleController extends AbstractActionController
{
    public function indexAction()
    {
		$this->brickConfig()->setActionMenu(array('create-edit'))
			->setActionTitle('文章列表');

		$factory = $this->dbFactory();
		$groupDoc = $factory->_m('Group')->findArticleGroup();
    	$optVal = $groupDoc->toMultioptions('label');
		
		return array(
			'optVal' => $optVal
		);
    }
    
    public function editAction()
    {
    	$id = $this->params()->fromRoute('id');
		$form = new EditForm();
		
		$factory = $this->dbFactory();
		$groupDoc = $factory->_m('Group')->addFilter('type', 'article')
    		->fetchOne();
    	$items = $groupDoc->toMultioptions('label');
    	$form->get('groupId')->setValueOptions($items);
    	
    	$co = $factory->_m('Article');
        $doc = null;
        if(empty($id)) {
            $doc = $co->create();
            $this->brickConfig()->setActionTitle('新建文章')
        		->setActionMenu(array('save'));
        } else {
        	$doc = $co->find($id);
        	$this->brickConfig()->setActionTitle('编辑文章: '.$doc->label)
        		->setActionMenu(array('save', 'delete'));
        }
        if(is_null($doc)) {
            throw new Class_Exception_AccessDeny('没有权限访问此内容，或者内容id不存在');
        }
    	$form->setData($doc->toArray());
        //$attachmentArr = $doc->attachment;
        if($this->getRequest()->isPost()) {
        	$postData = $this->getRequest()->getPost();
        	$form->setData($postData);
        	if($form->isValid()) {
	        	$doc->setFromArray($form->getData());
	    		$attaUrl	= $postData->get('attaUrl');
				$attaName	= $postData->get('attaName');
				$attaType	= $postData->get('attaType');
				
				if(!is_null($attaUrl)) {
					$doc->setAttachments($attaUrl, $attaName, $attaType);
				}
	            if(is_null($id)) {
	            	$fsa = $this->getServiceLocator()->get('Fucms\Session\Admin');
					$doc->created = date('Y-m-d H:i:s');
					$doc->createdBy = $fsa->getRoleId();
					$doc->createdByAlias = $fsa->getUserData('loginName');
	            }
	            $doc->save();
	            $this->flashMessenger()->addMessage('文章:'.$doc->label.' 已经成功保存');
	            return $this->redirect()->toRoute(null, array('action' => 'index', 'controller' => 'article'));
        	}
        }
        	
        $co = $factory::_m('Info');
		$infoDoc = $co->fetchOne();
		
		$thumbWidth = empty($infoDoc->thumbWidth) ? 200 : $infoDoc->thumbWidth;
		$thumbHeight = empty($infoDoc->thumbHeight) ? 200 : $infoDoc->thumbHeight;
		$siteId = $this->siteConfig('remoteSiteId');
		
		$time = time();
		$fileServerKey = 'gioqnfieowhczt7vt87qhitonqfn8eaw9y8s90a6fnvuzioguifeb';
		$sig = md5($siteId.$time.$fileServerKey);
        
		return array(
			'form'			=> $form,
			'article'		=> $doc,
			'thumbWidth'	=> $thumbWidth,
			'thumbHeight'	=> $thumbHeight,
			'time'			=> $time,
			'sig'			=> $sig
		);
    }
    
    public function deleteAction()
    {
		$id = $this->params()->fromRoute('id');
    	$factory = $this->dbFactory();
    	$co = $factory->_m('Article');
    	$doc = $co->find($id);
    	if(is_null($doc)) {
            throw new Class_Exception_AccessDeny('没有权限访问此内容，或者内容id不存在');
        }
        $doc->delete();
        $this->flashMessenger()->addMessage('文章:'.$doc->label.'已删除！');
		return $this->redirect()->toRoute(null, array('action' => 'index', 'controller' => 'article'));
    }
}
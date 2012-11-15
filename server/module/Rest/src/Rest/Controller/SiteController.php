<?php
class Rest_SiteController extends Zend_Rest_Controller 
{
	public function init()
	{
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
	}
	
	public function indexAction()
	{
		$currentPage = $this->getRequest()->getParam('page');
		
		$pageSize = 40;
		if(empty($currentPage)) {
			$currentPage = 1;
		}
		
		$table = new Zend_Db_Table('site');
		$select = $table->select();
		
//        foreach($this->getRequest()->getParams() as $key => $value) {
//            if(substr($key, 0 , 7) == 'filter_') {
//                $field = substr($key, 7);
//                switch($field) {
//                    case 'page':
//            			if(intval($value) != 0) {
//            				$currentPage = $value;
//            			}
//                        $result['currentPage'] = intval($value);
//            		    break;
//                }
//            }
//        }
		$select->limit($pageSize, $pageSize * ($currentPage - 1));
		$data = $table->fetchAll($select);
		
		$dataSize = 480;
		
		$result = array();
		$result['data'] = $data->toArray();
        $result['dataSize'] = $dataSize;
        $result['pageSize'] = $pageSize;
        $result['currentPage'] = $currentPage;
		
		$this->_helper->json($result);
	}
	
	public function getAction()
	{
		
	}
	
	public function postAction()
	{
		$data = file_get_contents('php://input');
		
		$jsonArray = Zend_Json::decode($data);
		
		$table = new Zend_Db_Table('site');
		$newRow = $table->createRow();
		
		$newRow->organizationCode = $jsonArray['organizationCode'];
		$newRow->siteFolder = $jsonArray['siteId'];
		$newRow->companyName = $jsonArray['label'];
		$newRow->containerPath = '/var/www/aqus-cms-0.6';
		$newRow->version = '0.6';
		$newRow->library = 'v1';
		$newRow->validToDate = date('Y-m-d', strtotime('+1 year'));
		$newRow->active = 1;
		$newRow->save();
		
		$newRow->subdomainName = $newRow->id.'.apple.fucms.com';
		$newRow->save();
		
		$this->getResponse()->setHeader('result', 'sucess');
		$this->_helper->json(array('remoteId' => $newRow->id, 'subdomainName' => $newRow->id.'.apple.fucms.com'));
	}
	
	public function putAction()
	{
		$id = $this->getRequest()->getParam('id');
		$data = file_get_contents('php://input');
		$jsonArray = Zend_Json::decode($data);
		
		$table = new Zend_Db_Table('site');
		$row = $table->find($id)->current();
		
		$row->setFromArray($jsonArray)
			->save();
		$this->getResponse()->setHeader('result', 'sucess');
		$this->_helper->json(array('remoteId' => $row->id));
	}
	
	public function deleteAction()
	{
		
	}
}
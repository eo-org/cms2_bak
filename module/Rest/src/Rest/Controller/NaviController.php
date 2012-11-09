<?php
namespace Rest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Serializer\Adapter\Json;

class NaviController extends AbstractRestfulController
{
	public function getList()
	{
		$filter = $this->getRequest()->getQuery(); 
		
		$currentPage = $filter['page'];
		$sIndex = $filter['sIndex'];
		$sOrder = intval($filter['sOrder']);
		$queryStr = $filter['query'];
		
		$pageSize = 20;
		if(empty($currentPage)) {
			$currentPage = 1;
		}
		
		$factory = $this->getServiceLocator()->get('Core\Mongo\Factory');
		$co = $factory->_m('Navi');
		$co->setFields(array('label'));
        $co->setPage($currentPage)->setPageSize($pageSize)
			->sort($sIndex, $sOrder);
		if($queryStr != 'none') {
			$queryArr = explode('-', $queryStr);
			foreach($queryArr as $qItem) {
				list($key, $val) = explode(':', $qItem);
				switch($key) {
					case '_id':
						$co->addFilter('_id', new MongoID($val));
						break;
					case 'label':
						$co->addFilter($key, new MongoRegex("/".$val."/"));
						break;
				}
			}
		}
		
		$data = $co->fetchAll(true);
		$dataSize = $co->count();
		
		$result = array();
		$result['data'] = $data;
        $result['dataSize'] = $dataSize;
        $result['pageSize'] = $pageSize;
        $result['currentPage'] = $currentPage;
		
		return $result;
	}
	
	public function get($id)
	{
	
	}
	
	public function create($data)
	{
	
	}
	
	public function update($id, $data)
	{
	
	}
	
	public function delete($id)
	{
		
	}
	
	public function treeSortAction()
	{
		$postData = $this->getRequest()->getPost();
		
		$treeId = $postData['treeId'];
    	$jsonStr = $postData['sortJsonStr'];
    	//$pageArr = Zend_Json::decode($jsonStr);
    	
    	$adapter = new Json();
		$pageArr = $adapter->unserialize($jsonStr);
    	
    	$descStr = "";
    	$factory = $this->dbFactory();
    	$co = $factory->_m('Navi_Link');
    	$docs = $co->setFields(array('label', 'parentId', 'sort', 'link', 'className', 'description'))
    		->addFilter('naviId', $treeId)
			->sort('sort', 1)
			->fetchDoc();
    	foreach($docs as $doc) {
    		$pageId = $doc->getId();
    		$newPageValue = $pageArr[$pageId];
    		$sort = intval($newPageValue['sort']);
    		$parentId = $newPageValue['parentId'];
    		if($doc->sort != $sort || $doc->parentId !== $parentId) {
    			$doc->sort = $sort;
    			$doc->parentId = (string)$parentId;
    			$doc->save();
    		}
    		$descStr.= $doc->label.', ';
    	}
    	
    	$treeDoc = $factory->_m('Navi')->find($treeId);
    	$treeDoc->setLeafs($docs);
    	$treeIndex = $treeDoc->buildIndex();
    	$treeDoc->naviIndex = $treeIndex;
    	if(strlen($descStr) > 45) {
    		$treeDoc->description = mb_substr($descStr, 0, 45, 'utf-8').' ... ';
    	} else {
    		$treeDoc->description = mb_substr($descStr, 0, -2, 'utf-8');
    	}
    	$treeDoc->save();
    	
    	$this->getResponse()->getHeaders()->addHeaderLine('result', 'sucess');
	}
}
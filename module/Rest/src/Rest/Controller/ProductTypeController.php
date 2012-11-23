<?php
namespace Rest\Controller;

use MongoId;
use MongoRegex;
use Zend\Mvc\Controller\AbstractRestfulController;

class ProductTypeController extends AbstractRestfulController
{
	public function getList()
	{
		$filter = $this->getRequest()->getQuery(); 
		
		$currentPage = $filter['page'];
		$sIndex = $filter['sIndex'];
		$sOrder = intval($filter['sOrder']);
		$qGroup = $filter['qGroup'];
		$queryStr = $filter['query'];
		
		$pageSize = 20;
		if(empty($currentPage)) {
			$currentPage = 1;
		}
		
		$factory = $this->dbFactory();
		$co = $factory->_m('Attributeset');
		$co->setFields(array());
        $co->setPage($currentPage)->setPageSize($pageSize)
			->sort($sIndex, $sOrder);
		
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
}
<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

class SiteController extends AbstractRestfulController 
{
	public function getList()
	{
		die('dont konw why');
	}
	
	public function get($id)
	{
		
	}
	
	public function create($dataArr)
	{
		$factory = $this->dbFactory();
		$serverCo = $factory->_m('Site');
		$serverDoc = $serverCo->create();
		
		$serverDoc->organizationCode = $dataArr['organizationCode'];
		$serverDoc->remoteSiteId = $dataArr['siteId'];
		$serverDoc->globalSiteId = $dataArr['globalId'];
		$serverDoc->domain = array(
			$dataArr['subdomainName']
		);
		$serverDoc->active = true;
		$serverDoc->save();
		
		$this->getResponse()->getHeaders()->addHeaderLine('result', 'sucess');
	}
	
	public function update($id, $data)
	{
		$dataStr = $data['model'];
		$dataArr = Json::decode($dataStr);
		
		$table = new Zend_Db_Table('site');
		$row = $table->find($id)->current();
		
		$row->setFromArray($dataArr)
			->save();
		$this->getResponse()->getHeaders()->addHeaderLine('result', 'sucess');
	}
	
	public function delete($id)
	{
		
	}
}
<?php
namespace Rest\Controller;

use MongoId;
use MongoRegex;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Serializer\Adapter\Json;

class AdSectionController extends AbstractRestfulController
{
	public function getList()
	{
		$factory = $this->dbFactory();
		$co = $factory->_m('Ad_Section');
		$data = $co->fetchAll(true);
        return $data;
	}
	
	public function get($id)
	{
	
	}
	
	public function create($data)
	{
		$modelString = file_get_contents('php://input');
		$jsonArray = Zend_Json::decode($modelString);
		
		$doc = App_Factory::_m('Ad_Section')->create($jsonArray);
		$doc->save();
		
		$this->getResponse()->getHeaders()->addHeaderLine('result', 'sucess');
		return array('id' => $doc->getId());
	}
	
	public function update($id, $data)
	{
		$factory = $this->dbFactory();
		$co = $factory->_m('Ad_Section');
		$doc = $co->find($id);
		$dataStr = $data['model'];
		$adapter = new Json();
		$dataArr = $adapter->unserialize($dataStr);
		$doc->setFromArray($dataArr);
		$doc->save();
		
		$this->getResponse()->getHeaders()->addHeaderLine('result', 'sucess');
		return array('id' => $id);
	}
	
	public function delete($id)
	{
		$factory = $this->dbFactory();
		$co = $factory->_m('Ad_Section');
		$doc = $co->find($id);
		$doc->delete();
		$this->getResponse()->getHeaders()->addHeaderLine('result', 'sucess');
	}
}
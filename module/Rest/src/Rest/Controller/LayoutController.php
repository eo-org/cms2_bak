<?php
namespace Rest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Json\Json;

class LayoutController extends AbstractRestfulController
{
	public function getList()
    {
    	
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
    
	public function saveStageAction()
	{
		$postData = $this->getRequest()->getPost();
		
		$jsonString = $postData['jsonString'];
		$jsonObj = Json::decode($jsonString, Json::TYPE_ARRAY);
		$layoutId = $jsonObj['layoutId'];
		$stagesObj = $jsonObj['stages'];
		
		$factory = $this->dbFactory();
		$co = $factory->_m('Layout');
		$doc = $co->find($layoutId);
		if(is_null($doc)) {
			$doc = $co->create();
			
		}
		$oldStage = $doc->stage;
		if(is_null($oldStage)) {
			$oldStage = array();
		}
		$brickCo = $factory->_m('Brick');
		foreach($oldStage as $v) {
			$deleted = true;
			foreach($stagesObj as $newValue) {
				if($v['stageId'] == $newValue['stageId']) {
					$deleted = false;
					break;
				}
			}
			if($deleted) {
				$brickCo->delete(array('stageId' => $v['stageId']));
			}
		}
		
		$doc->stage = $stagesObj;
		$doc->save();
		
		$this->getResponse()->getHeaders()->addHeaderLine('result', 'success');
	}
}
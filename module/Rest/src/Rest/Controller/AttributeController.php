<?php
namespace Rest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Json\Json;
use Zend\View\Model\JsonModel;

class AttributeController extends AbstractRestfulController
{
	public function getList()
	{
		$attributesetId = $this->getRequest()->getHeader('X-Attributeset-Id')->getFieldValue();
		$dm = $this->documentManager();
		$attributesetDoc = $dm->getRepository('Cms\Document\Attributeset')->findOneById($attributesetId);
		
		$attributeDocs = $attributesetDoc->getAttributeList();
		
		$data = array();
		foreach($attributeDocs as $attribute) {
			$data[] = $attribute->getArrayCopy();
		}
		
		return new JsonModel($data);
	}
	
	public function get($id)
	{
		
		
// 		$data = array('found');
		
//         return new JsonModel($data);
	}
	
	public function create($data)
	{
		$attributesetId = $this->getRequest()->getHeader('X-Attributeset-Id')->getFieldValue();
		$dm = $this->documentManager();
		$attributesetDoc = $dm->getRepository('Cms\Document\Attributeset')->findOneById($attributesetId);
		
		$dataStr = $data['model'];
		$dataArr = Json::decode($dataStr, Json::TYPE_ARRAY);
		
		$attributeDoc = $attributesetDoc->createAttribute();
		
		$attributeDoc->exchangeArray($dataArr);
		
		$attributesetDoc->addAttribute($attributeDoc);
		
		$dm->persist($attributesetDoc);
		$dm->flush();
		
		return array('id' => $attributeDoc->getId());
	}
	
	public function update($id, $data)
	{
		$attributesetId = $this->getRequest()->getHeader('X-Attributeset-Id')->getFieldValue();
		$dm = $this->documentManager();
		$attributesetDoc = $dm->getRepository('Cms\Document\Attributeset')->findOneById($attributesetId);
		
		$attributeDoc = $attributesetDoc->getAttributeById($id);
		$dataStr = $data['model'];
		$dataArr = Json::decode($dataStr, Json::TYPE_ARRAY);
		
		$attributeDoc->exchangeArray($dataArr);
		
		//$attributesetDoc->addAttribute($attributeDoc);
		
		$dm->persist($attributeDoc);
		$dm->flush();
		
		return array('id' => $attributeDoc->getId());
	}
	
	public function delete($id)
	{
		$attributesetId = $this->getRequest()->getHeader('X-Attributeset-Id')->getFieldValue();
		$dm = $this->documentManager();
		$attributesetDoc = $dm->getRepository('Cms\Document\Attributeset')->findOneById($attributesetId);
		$attributesetDoc->removeAttribute($id);
		$dm->persist($attributesetDoc);
		$dm->flush();
		return array('id' => $id);
	}
}
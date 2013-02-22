<?php
namespace Cms\Document;

use Core\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(
 * 		collection="product"
 * )
 * 
 * */
class Product extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;
	
	/** @ODM\Field(type="string")  */
	protected $attributesetId;
	
	/** @ODM\Field(type="string")  */
	protected $groupId;
	
	/** @ODM\Field(type="string")  */
	protected $label;
	
	/** @ODM\Field(type="string")  */
	protected $name;
	
	/** @ODM\Field(type="string")  */
	protected $sku;
	
	/** @ODM\Field(type="float")  */
	protected $price;
	
	/** @ODM\Field(type="string")  */
	protected $fulltext;
	
	/** @ODM\Field(type="string")  */
	protected $introicon;
	
	/** @ODM\Field(type="string")  */
	protected $introtext;
	
	/** @ODM\Field(type="string")  */
	protected $metakey;
	
	/** @ODM\Field(type="int")  */
	protected $sort;
	
	/** @ODM\Field(type="hash")  */
	protected $attachment;
	
	/** @ODM\Field(type="string")  */
	protected $status;

	public function setAttachment($urlArr, $nameArr, $typeArr)
	{
		if(count($urlArr) == 0) {
			return true;
		}
		if(count($urlArr) != count($nameArr) || count($urlArr) != count($typeArr)) {
			throw new Exception('attachment count does not match each other!');
		}

		$attachment = array();
		foreach($typeArr as $key => $type) {
			$attachment[] = array('filetype' => $type, 'filename' => $nameArr[$key], 'urlname' => $urlArr[$key]);
		}
		$this->attachment = $attachment;
	}

	public function toggleTrash()
	{
		if($this->status == 'trash') {
			$this->status = 'publish';
		} else {
			$this->status = 'trash';
		}
		$this->save();
	}
}
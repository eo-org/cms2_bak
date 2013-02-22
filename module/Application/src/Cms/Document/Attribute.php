<?php
namespace Cms\Document;

use Core\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Attribute extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;
	
	/** @ODM\Field(type="string")  */
	protected $type;
	
	/** @ODM\Field(type="string")  */
	protected $code;
	
	/** @ODM\Field(type="string")  */
	protected $label;
	
	/** @ODM\Field(type="string")  */
	protected $description;
	
	/** @ODM\Field(type="hash")  */
	protected $options;
	
	/** @ODM\Field(type="boolean")  */
	protected $required;
	
	/** @ODM\Field(type="int")  */
	protected $sort;
	
	public function getElement()
	{
		$element = null;
		switch($this->type) {
			case 'select':
				break;
			case 'input':
				break;
		}
		return $element;
	}
	
	public function exchangeArray($data)
	{
		$this->type = $data['type'];
		$this->code = $data['code'];
		$this->label = $data['label'];
		$this->description = $data['description'];
		$this->required = $data['required'];
		$this->sort = $data['sort'];
		
		$options = array();
		foreach($data['optionsCode'] as $key => $code) {
			$options[$code] = $data['optionsLabel'][$key];
		}
		$this->options = $options;
	}
	
	public function getArrayCopy()
	{
		return array(
			'id' => $this->id,
			'type' => $this->type,
			'code' => $this->code,
			'label' => $this->label,
			'description' => $this->description,
			'options' => $this->options,
			'required' => $this->required,
			'sort' => $this->sort
		);
	}
}
<?php
namespace Cms\Document;

use Core\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zend\Form\Element;

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
	
	public function getFormElement()
	{
		$element = null;
		switch($this->type) {
			case 'select':
				$element = new Element\Select($this->code);
				$element->setOptions(array('value_options' => $this->options));
				break;
			case 'input':
				$element = new Element\Text($this->code);
				break;
		}
		$element->setLabel($this->label);
		
// 			->setAttributes());
		return $element;
	}
	
	public function getOptionLabel($optKey)
	{
		foreach($this->options as $key => $keyLabel) {
			if($optKey === $key) {
				return $keyLabel;
			}
		}
		return null;
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
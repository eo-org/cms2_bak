<?php
namespace Admin\Form\Layout;

use Zend\Form\Form;

class EditDefaultForm extends Form
{
    public function __construct()
    {
    	parent::__construct('layout-edit-default');
    	
		$this->add(array(
			'name' => 'hideHead',
			'type' => 'Zend\Form\Element\Checkbox',
			'options' => array('label' => '隐藏页头')
		));
		$this->add(array(
			'name' => 'hideTail',
			'type' => 'Zend\Form\Element\Checkbox',
			'options' => array('label' => '隐藏页脚')
		));
    }
}
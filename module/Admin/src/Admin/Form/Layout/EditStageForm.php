<?php
namespace Admin\Form\Layout;

use Zend\Form\Form;

class EditStageForm extends Form
{
    public function __construct()
    {
		parent::__construct('layout-stage-edit');
		
		$this->add(array(
    		'name' => 'uniqueId',
    		'attributes' => array('type' => 'text'),
    		'options' => array('label' => 'STAGE ID')
    	));
	}
}
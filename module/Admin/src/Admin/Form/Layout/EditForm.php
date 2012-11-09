<?php
namespace Admin\Form\Layout;

use Zend\Form\Form;

class EditForm extends Form
{
    public function __construct()
    {
    	parent::__construct('layout-create');
    	
    	$this->add(array(
    		'name' => 'label',
    		'attributes' => array('type' => 'text'),
    		'options' => array('label' => '标题[中文]')
    	));
    	$this->add(array(
    		'name' => 'type',
    		'type' => 'Zend\Form\Element\Select',
    		'options' => array('label' => '数据类型', 'value_options' => array(
    			'frontpage' => '综合页面',
				'list' => '文章列表',
				'product-list' => '产品列表',
				'book' => '手册'
    		))
    	));
    	$this->add(array(
    		'name' => 'resourceAlias',
    		'attributes' => array('type' => 'text'),
    		'options' => array('label' => '数据别名[a-z]')
		));
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
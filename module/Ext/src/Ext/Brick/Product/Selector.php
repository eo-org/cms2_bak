<?php
namespace Ext\Brick\Product;

use Ext\Brick\AbstractExt;

class Selector extends AbstractExt
{
    public function prepare()
    {
    	$layoutFront = $this->getLayoutFront();
    	
    	$context = $layoutFront->getContext();
    	if($context->getType() != 'product-list') {
    		$this->_disableRender = 'no-resource';
    		return false;
    	}
    	$currentQuery = $context->getQuery()->toArray();
    	$filters = $this->getParam('filters');
    	
    	$filterArr = \Zend\Json\Json::decode($filters, \Zend\Json\Json::TYPE_ARRAY);
    	
//     	$twigEnv = \Ext\Twig\View::getTwigEnv();
//     	$twigEnv->addFunction(new \Twig_SimpleFunction('check', function() {
//     		return true;
//     	}));
    	$this->view->currentQuery = $currentQuery;
    	$this->view->filterArr = $filterArr;
    }
    
	public function getFormClass()
    {
    	return 'Ext\Form\Product\Selector';
    }
    
    public function getTplList()
    {
    	return array('view' => 'product\selector\view.tpl');
    }
}
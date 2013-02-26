<?php
namespace Ext\Brick\Search;

use MongoRegex;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Null as NullAdapter;
use Ext\Brick\AbstractExt;

class Result extends AbstractExt
{
    public function prepare()
    {    	
    	$sm = $this->sm;
		
		$matches = $this->_controller->getEvent()->getRouteMatch();
		$routeMatchParams = $matches->getParams();
		$routeType = $matches->getMatchedRouteName();

		return $this->getController()->getRequest()->getQuery($param, $default);
		
		$type = $this->getController()->getRequest()->getQuery('type');
    	$keywords = $this->getController()->getRequest()->getQuery('kyewords');
    	$page = $this->getController()->getRequest()->getQuery('page');
    	
    	if(empty($page)) {
    		$page = 1;
    	}
    	
    	$pageSize = 1;
    	$data = array();
    	if(!empty($keywords)) {
    		$factory = $this->dbFactory();
    		if(is_null($type) || $type == 'product') {
    			$type = 'product';
    			$co = $factory->_m('Product');
    			$co->setFields(array('label', 'introtext', 'introicon', 'attributeDetail', 'created'))
    				->addFilter('$or', array(
    					array('label' => new MongoRegex("/".$keywords."/i")),
    					array('name' =>	new MongoRegex("/^".$keywords."/i")),
    				))->setPage($page)
    				->setPageSize($pageSize);
    		} else {
    			$co = $factory->_m('Article');
    			$co->setFields(array('label', 'introtext', 'introicon', 'created'))
    				->addFilter('label', new MongoRegex("/".$keywords."/i"))
    				->setPage($page)
    				->setPageSize($pageSize);
    		}
    	}
    	
		$dataSize = $co->count();
		$data = $co->fetchDoc();
		
		$paginator = new Paginator(new NullAdapter($dataSize));
		Paginator::setDefaultScrollingStyle('Sliding');
		$paginator->setCurrentPageNumber($page)
			->setItemCountPerPage($pageSize);
		$pages = $paginator->getPages();
		
		$this->view->rowset = $data;
    	$this->view->type = $type;
    	
    	$this->view->routeType = 'application/search';
		$this->view->pages = $pages;
		$this->view->routeMatchParams = $routeMatchParams;
		$this->view->getQueryParams = array(
			'type' => 'type='.$type,
			'keywords' => 'keywords='.$keywords,
		);
    }
    
    public function getTplList()
    {
    	return array('view' => 'search\result\view.tpl');
    }
}
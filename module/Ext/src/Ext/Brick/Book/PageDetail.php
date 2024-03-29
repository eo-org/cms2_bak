<?php
namespace Ext\Brick\Book;

use MongoId;
use Ext\Brick\AbstractExt;

class PageDetail extends AbstractExt
{
    public function prepare()
    {
    	$sm = $this->_controller->getServiceLocator();
		$layoutFront = $sm->get('Fucms\Layout\Front');
		$context = $layoutFront->getContext();
		
		if($context->getType() != 'book') {
			throw new Exception('this extension is only suitable for a book typed layout!');
		}
		
		$rm = $layoutFront->getRouteMatch();
    	$pageId = $rm->getParam('pageId');
    	
    	if(is_null($pageId)) {
    		$pageId = 'index';
    	}
    	
    	$factory = $this->dbFactory();
    	$co = $factory->_m('Book_Page');
    	$pageDoc = $co->addFilter('$or', array(
	    		array('_id' => new MongoId($pageId)),
	    		array('alias' => $pageId)
	    	))->addFilter('bookId', $context->getId())->fetchOne();
    	$this->view->doc = $pageDoc;
    }
    
    public function getFormClass()
    {
    	return 'Ext\Form\Book\PageDetail';
    }
    
    public function getTplList()
    {
    	return array('view' => 'book\pagedetail\view.tpl');
    }
}
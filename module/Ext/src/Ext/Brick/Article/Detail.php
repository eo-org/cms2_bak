<?php
namespace Ext\Brick\Article;

use Ext\Brick\AbstractExt;

class Detail extends AbstractExt
{
    public function prepare()
    {
    	$sm = $this->_controller->getServiceLocator();
    	$layoutFront = $sm->get('Fucms\Layout\Front');
		$context = $layoutFront->getContext();
    	$rm = $layoutFront->getRouteMatch();
    	$articleId = $rm->getParam('id');
    	
    	$factory = $this->dbFactory();
    	$articleDoc = $factory->_m('Article')->find($articleId);
        if($context->getType() == 'article' && $articleDoc != null) {
	        $title = $articleDoc->label;
	        if($this->getParam('showHits') == 'y') {
	        	$articleDoc->hits++;
	        	$articleDoc->save();
	        }
        } else {
        	$title = '文章找不到';
        }
        $this->view->row = $articleDoc;
    }
    
    public function getFormClass()
    {
    	return 'Ext\Form\Article\Detail';
    }
    
    public function getTplList()
    {
    	return array(
    		'view' => 'article\detail\view.tpl'
    	);
    }
}
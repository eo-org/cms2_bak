<?php
namespace Application\Brick;

use Brick\Module\AbstractBrick;

class Attachment extends AbstractBrick
{
	public function prepare()
    {
    	$layoutFront = $this->_controller->getServiceLocator()->get('Fucms\Layout\Front');
    	$resDoc = $layoutFront->getContext()->getResourceDoc();
    	if(is_null($resDoc) || !is_object($resDoc)) {
    		$attachment = null;
    	} else {
    		$attachment = $resDoc->attachment;
    	}
    	
    	$download = array();
    	if(is_null($attachment) || count($attachment) == 0) {
    		$this->_disableRender = true;
    	} else {
    		foreach($attachment as $atta) {
    			if($atta['filetype'] == 'download') {
    				$download[] = $atta;
    			}
    		}
    	}
    	
    	if(count($download) == 0) {
    		$this->_disableRender = true;
    	}
    	print_r($download);
    	$this->view->download = $download;
    }
    
    public function getClass()
    {
    	return null;
    }
    
    public function getTplList()
    {
    	return array('view.tpl' => 'attachment/view.tpl');
    }
}
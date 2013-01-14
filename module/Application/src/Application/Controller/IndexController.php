<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Fucms\Session\Admin as SessionAdmin;

use Document\User;

class IndexController extends AbstractActionController
{
	public $brickRegister = null;
	public $siteDoc = null;
	
    public function indexAction()
    {
    	$localCssMode = $this->params()->fromQuery('local-css-mode');
    	if($localCssMode == 'activate') {
    		$sessionAdmin = new SessionAdmin();
    		$sessionAdmin->addUserData('localCssMode', 'active');
    	} elseif($localCssMode == 'deactivate')  {
    		$sessionAdmin = new SessionAdmin();
    		$sessionAdmin->addUserData('localCssMode', 'deactivate');
    	}
    	
    	return new viewModel();
    	
//     	$sm = $this->getServiceLocator();
//     	$lf = $sm->get('Fucms\Layout\Front');
//     	$layoutDoc	= $lf->getLayoutDoc();
//     	$brickRegister = $sm->get('Brick\Register');
    	
//     	$brickViewList = $brickRegister->renderAll();
//     	$stageList = $layoutDoc->stage;
    	
//         return array(
//         	'hideHead' => $layoutDoc->hideHead,
//         	'hideTail' => $layoutDoc->hideTail,
//         	'layoutDoc' => $layoutDoc->getId(),
//         	'stageList' => $stageList,
//         	'brickViewList' => $brickViewList
//         );
    }
    
//     public function setBrickRegister($br)
//     {
//     	$this->brickRegister = $br;
//     }
    
//     public function getBrickRegister()
//     {
//     	return $this->brickRegister;
//     }
    
//     public function setSiteDoc($siteDoc)
//     {
//     	$this->siteDoc = $siteDoc;
//     }
    
//     public function getSiteDoc()
//     {
//     	return $this->siteDoc;
//     }
}

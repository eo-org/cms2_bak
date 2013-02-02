<?php
namespace Cms;

use Zend\Stdlib\DispatchableInterface, Zend\Stdlib\RequestInterface as Request, Zend\Stdlib\ResponseInterface as Response;
use Zend\Mvc\InjectApplicationEventInterface, Zend\EventManager\EventInterface as Event;
use Zend\ServiceManager\ServiceLocatorAwareInterface, Zend\ServiceManager\ServiceLocatorInterface;

class ApplicationController implements
	DispatchableInterface,
    InjectApplicationEventInterface,
    ServiceLocatorAwareInterface
{
	/**
	 * 
	 * @var \Zend\Mvc\MvcEvent
	 */
	protected $mvcEvent;
	
	/**
	 * 
	 * @var \Zend\ServiceManager\ServiceManager
	 */
	protected $serviceManager;
	
	public function dispatch(Request $request, Response $response = null)
	{
		$this->getEvent()->setTarget($this);
// 			$controller = $e->getTarget();
// 			$sm = $controller->getServiceLocator();
		
// 			$factory = $controller->dbFactory();
// 			$co = $factory->_m('Info');
// 			$infoDoc = $co->fetchOne();
		
// 			$locale = 'zh_CN';
// 			if(!is_null($infoDoc)) {
// 				$locale = $infoDoc->language;
// 			}
// 			$translator = Translator::factory(array(
// 				'locale' => $locale,
// 				'translation_file_patterns' => array(
// 					array(
// 						'type'			=> 'gettext',
// 						'base_dir'		=> __DIR__ . '/language',
// 						'pattern'		=> '%s.mo',
// 					)
// 				)
// 			));
// 			$sm->setService('translator', $translator);
		
		$layoutFront = $this->serviceManager->get('Cms\Layout\Front');
		$layoutFront->initLayout($this->getEvent());
		
		//return $response;
		//return $response->setContent('fefe');
	}
	
	public function layout($template = null)
	{
		$viewModel = $this->getEvent()->getViewModel();
		if(null !== $template) {
			;$viewModel->setTemplate((string) $template);
		}
		return $viewModel;
	}
	
	public function setEvent(Event $event)
	{
		$this->mvcEvent = $event;
	}
	
    public function getEvent()
    {
    	return $this->mvcEvent;
    }
    
    public function setServiceLocator(ServiceLocatorInterface $serviceManager)
    {
    	$this->serviceManager = $serviceManager;
    }
    
    public function getServiceLocator()
    {
    	return $this->serviceManager;
    }
}
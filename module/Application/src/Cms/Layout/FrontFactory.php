<?php
namespace Cms\Layout;

use Zend\ServiceManager\FactoryInterface, Zend\ServiceManager\ServiceLocatorInterface;

class FrontFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$application = $serviceLocator->get('Application');
		$rm = $application->getMvcEvent()->getRouteMatch();
		
		$layoutFront = new Front($serviceLocator);
		$contextFactory = new ContextFactory($serviceLocator);
		$context = $contextFactory->getContext($rm);
		if(!is_null($context)) {
			$layoutFront->setContext($context);
		}
		return $layoutFront;
	}
}
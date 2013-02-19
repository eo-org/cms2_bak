<?php
namespace User\Acl\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class AclListener implements ListenerAggregateInterface
{
    protected $listeners = array();
    
    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('route', array($this, 'onRoute'), -10);
    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
    
    public function onRoute(MvcEvent $e)
    {
    	$sm = $e->getApplication()->getServiceManager();
    	
    	$dm = $sm->get('DocumentManager');
    	$configDoc = $dm->createQueryBuilder('User\Document\Config')
			->getQuery()
			->getSingleResult();
		if(is_null($configDoc)) {
			$configDoc = new \User\Document\Config();
		}
		$aclSetting = $configDoc->getAcl();
		if($aclSetting == 'enable') {
			$layoutFront = $sm->get('Cms\Layout\Front');
			$rm = $e->getRouteMatch();
			$layoutFront->setRouteMatch($rm);
			$context = $layoutFront->getContext();
			if(is_null($context)) {
				return;
			}
			if($context->getType() == 'article' || $context->getType() == 'article-list') {
				$groupItemId = $context->getGroupItemId();
// 				$groupItemResourceDoc = $dm->getRepository('User\Document\Resource')->findOneByResourceId($groupItemId);
				
// 				if(is_null($groupItemResourceDoc)) {
// 					return;
// 				}
				$protectedResourceId = $configDoc->getProtectedResourceId();
				if(in_array($groupItemId, $protectedResourceId)) {
					die('not allowed!');
				}
			}
		}
    }
}

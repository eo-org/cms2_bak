<?php
namespace Cms\Cache\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

use Zend\Cache\StorageFactory;
use Fucms\Session\Admin as SessionAdmin;
use Cms\Cache\Manager as CacheManager;

class CacheListener implements ListenerAggregateInterface
{
    protected $listeners = array();
    protected $cacheManager;

    public function init($sm, $rm)
    {
    	$storage = StorageFactory::factory(array(
    		'adapter' => 'Cms\Cache\Storage\Adapter\Mongo',
    	));
    	 
    	$this->cacheManager = new CacheManager($storage, $sm, $rm);
    }
    
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
    	$sessionAdmin = new SessionAdmin();
    	if(!$sessionAdmin->isLogin()) {
	        $this->listeners[] = $events->attach('route', array($this, 'onRoute'), -1);
	        $this->listeners[] = $events->attach('finish', array($this, 'onFinish'), -100);
    	}
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

    /**
     * Load the page contents from the cache and set the response.
     *
     * @param  \Zend\Mvc\MvcEvent             $e
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function onRoute(MvcEvent $e)
    {
    	$sm = $e->getApplication()->getServiceManager();
    	$rm = $e->getRouteMatch();
    	$this->init($sm, $rm);
    	
    	$cacheManager = $this->getCacheManager();
    	$cacheContent = $cacheManager->load();
    	
    	if(is_null($cacheContent)) {
    		return;
    	} else {
	    	$response = $e->getResponse();
	    	$response->setContent($cacheContent);
	    	
	    	return $response;
    	}
    }

    /**
     * Save page contents to the cache
     *
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function onFinish(MvcEvent $e)
    {
    	$response = $e->getResponse();
    	$this->cacheManager->save($response);
    }

    public function getCacheManager()
    {
    	return $this->cacheManager;
    }
}

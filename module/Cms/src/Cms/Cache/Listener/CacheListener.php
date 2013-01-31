<?php
namespace Cms\Cache\Listener;

use Zend\EventManager\ListenerAggregateInterface;
//use Zend\Http\PhpEnvironment\Request as HttpRequest;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

use Zend\Cache\StorageFactory;

use Fucms\Session\Admin as SessionAdmin;

class CacheListener implements ListenerAggregateInterface
{
    /**
     * @var array
     */
    protected $listeners = array();

    /**
     * @var CacheService
     */
    protected $cacheService;

    public function __construct()
    {
    	$this->cacheService = StorageFactory::factory(array(
    		'adapter' => 'Cms\Cache\Storage\Adapter\Mongo',
    		
    	));
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
	        $this->listeners[] = $events->attach('route', array($this, 'onRoute'), 100);
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
    	echo 'on route event triggered<br />';
    	$key = 'index';
    	
    	$dm = $e->getApplication()->getServiceManager()->get('DocumentManager');
    	
    	$this->cacheService->setDocumentManager($dm);
    	
    	$cacheContent = $this->cacheService->getItem($key, $success);
    	
    	if(!$success) {
    		echo "load key failed<br />";
    		return;
    	} else {
    		$this->skipUpdate = true;
    		
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
    	if($this->skipUpdate) {
    		return;
    	}
    	echo 'on finish event triggered<br />';
    	$key = 'index';
    	$response = $e->getResponse();
    	$cacheContent = $response->getContent();
    	
    	//$values = "<html><body>This is a pig!</body></html>";
    	$this->cacheService->setItem($key, $cacheContent);
    }

    /**
     * @return \StrokerCache\Service\CacheService
     */
    public function getCacheService()
    {
        return $this->cacheService;
    }
}

<?php
namespace Cms\Cache;

class Manager
{
	public $storage;
	
	public $sm;
	
	public $rm;
	
	public $skipUpdate = false;
	
	protected $key;
	//3600 * 3 = 10800
	protected $ttl = 10800;
	
	public function __construct($storage, $sm, $rm)
	{
		$this->storage = $storage;
		$this->sm = $sm;
		$this->rm = $rm;
	}
	
	public function save($response)
	{
		if($this->skipUpdate) {
			return;
		}
		
		$key = $this->getKey();
		$cacheContent = $response->getContent();
		$this->storage->setItem($key, $cacheContent);
	}
	
	public function load()
	{
		if(!$this->shouldCache()) {
			$this->skipUpdate = true;
			return null;
		}
		
		$key = $this->getKey();
		$dm = $this->sm->get('DocumentManager');
		$this->storage->setDocumentManager($dm);
		$success = false;
		$cacheDoc = $this->storage->getItem($key, $success);
		if($success) {
			$updated = $cacheDoc->getUpdated();
			
			$mtime = microtime();
			$now = explode(" ",$mtime);
			$now = $now[1];
			
			if($now - $updated->getTimestamp() > 120) {
				return null;
			} else {
				$this->skipUpdate = true;
				return $cacheDoc->getContent();
			}
		}
		return null;
	}
	
	public function getKey()
	{
		if(!$this->key) {
			$routeName = $this->rm->getMatchedRouteName();
			
			$params = $this->rm->getParams();
			unset($params['__NAMESPACE__']);
			unset($params['controller']);
			unset($params['action']);
			
			$this->key = $routeName.'-'.implode('-', $params);
		}
		return $this->key;
	}
	
	public function shouldCache()
	{
		$routeName = $this->rm->getMatchedRouteName();
		if(substr($routeName, 0, 11) == 'application') {
			return true;
		}
		
		return false;
	}
}
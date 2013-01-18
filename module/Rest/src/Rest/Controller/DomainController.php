<?php
namespace Rest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Json\Json;
use Zend\View\Model\JsonModel;
use Doctrine;
use Document\ServerCenter\Site;

class DomainController extends AbstractRestfulController
{
	public function getList()
	{
		$remoteSiteId = $this->siteConfig('remoteSiteId');
		$dm = $this->documentManager();
		
		$site = $dm->createQueryBuilder('Document\ServerCenter\Site')
			->field('remoteSiteId')->equals($remoteSiteId)
			->hydrate(false)
			->getQuery()
			->getSingleResult();
		
		$domains = $site['domains'];
		foreach($domains as &$domain) {
			$domain['id'] = $domain['_id']->{'$id'};
			unset($domain['_id']);
		}
		return new JsonModel($domains);
	}
	
	public function get($id)
	{
	
	}
	
	public function create($data)
	{
		$remoteSiteId = $this->siteConfig('remoteSiteId');
		$dm = $this->documentManager();
		
		$dataStr = $data['model'];
		$dataArr = Json::decode($dataStr, Json::TYPE_ARRAY);
		
		$domainName = $dataArr['domainName'];
		$site = $dm->createQueryBuilder('Document\ServerCenter\Site')
			->field('domains.domainName')->equals($domainName)
			->getQuery()
			->getSingleResult();
		
		if($site !== null) {
			$this->getResponse()->getHeaders()->addHeaderLine('result', 'failed');
			return "域名".$domainName."已经绑定其他网站!请联系客服.";
		}
		
		
		
		$site = $dm->createQueryBuilder('Document\ServerCenter\Site')
			->field('remoteSiteId')->equals($remoteSiteId)
			->getQuery()
			->getSingleResult();
		$domains = $site->getDomains();
		if(count($domains) >= 4) {
			$this->getResponse()->getHeaders()->addHeaderLine('result', 'failed');
			return "单个网站最多绑定3个域名";
		}
		$domain = new \Document\ServerCenter\Domain();
		$domain->setFromArray($dataArr);
		
		$site->addDomain($domain);
		$dm->persist($site);
		$dm->flush();
		
		$this->getResponse()->getHeaders()->addHeaderLine('result', 'sucess');
		return $domain->getId();
	}
	
	public function update($id, $data)
	{
		
	}
	
	public function delete($id)
	{
		$remoteSiteId = $this->siteConfig('remoteSiteId');
		$dm = $this->documentManager();
		
		$site = $dm->createQueryBuilder('Document\ServerCenter\Site')
			->field('remoteSiteId')->equals($remoteSiteId)
			->getQuery()
			->getSingleResult();
		
		if($site->removeDomain($id)) {
			$dm->persist($site);
			$dm->flush();
			
			$this->getResponse()->getHeaders()->addHeaderLine('result', 'sucess');
		} else {
			$this->getResponse()->getHeaders()->addHeaderLine('result', 'failed');
			$this->getResponse()->getHeaders()->addHeaderLine('responseText', "default domain or domain not found!");
		}
	}
}
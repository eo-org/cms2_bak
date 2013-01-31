<?php
namespace DoctrineMongo;

use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;
use Doctrine\Common\Persistence\PersistentObject,
Doctrine\ODM\MongoDB\DocumentManager,
Doctrine\ODM\MongoDB\Configuration,
Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver,
Doctrine\MongoDB\Connection;

class Module implements BootstrapListenerInterface
{
	public function onBootstrap(EventInterface $event)
	{
		$application = $event->getTarget();
		$sm = $application->getServiceManager();
		$siteConfig = $sm->get('ConfigObject\EnvironmentConfig');
		$dbName = $siteConfig->dbName;
		
		AnnotationDriver::registerAnnotationClasses();
		$config = new Configuration();
		$config->setDefaultDB($dbName);
		
		$config->setProxyDir(BASE_PATH . '/cms2/doctrineCache');
		$config->setProxyNamespace('DoctrineMongoProxy');
		$config->setHydratorDir(BASE_PATH . '/cms2/doctrineCache');
		$config->setHydratorNamespace('DoctrineMongoHydrator');
		$config->setMetadataDriverImpl(AnnotationDriver::create(BASE_PATH.'/class'));
		
		$config->setAutoGenerateHydratorClasses(true);
		$config->setAutoGenerateProxyClasses(true);
		
		$dm = DocumentManager::create(new Connection(), $config);
		PersistentObject::setObjectManager($dm);
		
		$sm->setService('DocumentManager', $dm);
	}
	
	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
	}

	public function getConfig()
	{
		return include __DIR__ . '/configs/module.config.php';
	}
}
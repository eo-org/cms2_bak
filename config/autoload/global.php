<?php
return array(
	/*
	'db' => array(
		'driver'	=> 'Pdo',
    	'dsn'		=> 'mysql:dbname=sitelist;host=58.51.194.8',
		'driver_options' => array(
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
		)
    ),
    */
    'mongo_db' => array(
        'driver'	=> 'MongoDb',
        'host'		=> '127.0.0.1',
    	'dbName'	=> 'cms',
    ),
    'service_manager' => array(
    	'invokables' => array(
    		'Core\Mongo\Factory' => 'Core\Mongo\Factory'
    	),
        'factories' => array(
			'Core\Mongo\Db\Adapter'		=> 'Core\Mongo\Db\AdapterServiceFactory',
    		'Zend\Db\Adapter\Adapter'	=> 'Zend\Db\Adapter\AdapterServiceFactory'
        ),
    ),
);
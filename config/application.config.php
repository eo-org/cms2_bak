<?php
return array(
    'modules' => array(
        'Application',
		'User',
		'Admin',
		'Rest',
    	'Disqus',
    	'DoctrineMongo',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php'
        ),
        'module_paths' => array(
            './module',
        	'./vendor'
        ),
    ),
	'service_manager' => array(
		'factories' => array('Fucms\SiteConfig' => function($serviceManager) {
			$siteConfig = new \Fucms\SiteConfig(include 'config/server.config.php');
			return $siteConfig;
		})
	),
);

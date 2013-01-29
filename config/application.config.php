<?php
return array(
    'modules' => array(
    	'DoctrineMongo',
    	'Ext',
        'Application',
		'Admin',
		'Rest',
    	'User',
    	'Disqus',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php'
        ),
        'module_paths' => array(
            './module'
        ),
    ),
);

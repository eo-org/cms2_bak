<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'User\Index'	=> 'User\Controller\IndexController',
			'User\Order'	=> 'User\Controller\OrderController',
			'User\Address'	=> 'User\Controller\AddressController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'user' => __DIR__ . '/../view',
        ),
    ),
    'router' => array(
        'routes' => array(
            'user' => array(
    			'type' => 'segment',
    			'options' => array(
    				'route' => '/user',
    				'defaults' => array(
    					'controller' => 'User\Index',
    					'action' => 'index'
    				)
    			),
    			'may_terminate' => true,
    			'child_routes' => array(
    				'login' => array(
    					'type'		=> 'literal',
    					'options'	=> array(
    						'route'		=> '/login',
    						'defaults'	=> array(
    							'controller' => 'User\Index',
    							'action' => 'login'
    						)
    					)
    				),
		    		'order' => array(
    					'type'    => 'segment',
		                'options' => array(
		                    'route'    => '/order[/:action]',
		                    'defaults' => array(
		    					'controller' => 'User\Order',
		                        'action' => 'index'
		                    ),
		                ),
						'params'  => array(
							'params' => array(
								'type' => 'wildcard',
							),
						),
    				),
    				'address' => array(
    					'type'    => 'segment',
		                'options' => array(
		                    'route'    => '/address[/:action]',
		                    'defaults' => array(
		    					'controller' => 'User\Address',
		                        'action' => 'index'
		                    ),
		                ),
						'params'  => array(
							'params' => array(
								'type' => 'wildcard',
							),
						),
    				)
    			)
            ),
        ),
    ),
);
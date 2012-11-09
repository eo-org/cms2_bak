<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'index'			=> 'Admin\Controller\IndexController',
			'navi'			=> 'Admin\Controller\NaviController',
			'article'		=> 'Admin\Controller\ArticleController',
			'book'			=> 'Admin\Controller\BookController',
			'product'		=> 'Admin\Controller\ProductController',
			'ad-section'	=> 'Admin\Controller\AdSectionController',
			'ad'			=> 'Admin\Controller\AdController',
			'user'			=> 'Admin\Controller\UserController',
			'system'		=> 'Admin\Controller\SystemController',
			'site'			=> 'Admin\Controller\SiteController',
			'brick'			=> 'Admin\Controller\BrickController',
			'brick.ajax'	=> 'Admin\Controller\BrickController',
			'group'			=> 'Admin\Controller\GroupController',
			'layout'		=> 'Admin\Controller\LayoutController',
			'layout.ajax'	=> 'Admin\Controller\LayoutController',
        ),
    ),
    'controller_plugins' => array(
    	'invokables' => array(
    		'brickConfig'	=> 'Brick\Helper\Controller\Config',
    		'dbFactory'		=> 'Core\Controller\Plugin\DbFactory',
    		'switchContext'	=> 'Core\Controller\Plugin\SwitchContext',
    	)
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'admin' => __DIR__ . '/../view',
        ),
        'template_map' => array(
    		'layout/head'			=> __DIR__ . '/../view/layout/head.phtml',
        	'layout/admin-toolbar'	=> __DIR__ . '/../view/layout/toolbar.phtml',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
    		'singleForm'			=> 'Core\View\Helper\SingleForm',
    		'brickConfigForm'		=> 'Core\View\Helper\BrickConfigForm',
            'tabForm'				=> 'Core\View\Helper\TabForm',
            'bootstrapRow'			=> 'Core\View\Helper\BootstrapRow',
            'bootstrapCollection'	=> 'Core\View\Helper\BootstrapCollection',
    		'outputImage'			=> 'Core\View\Helper\OutputImage',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
    			'type' => 'segment',
    			'options' => array(
    				'route' => '/admin',
    				'defaults' => array(
    					'controller' => 'index',
    					'action' => 'index'
    				)
    			),
    			'may_terminate' => true,
    			'child_routes' => array(
		    		'childroutes' => array(
    					'type'    => 'segment',
		                'options' => array(
		                    'route'    => '[/:controller][/:action]',
		                    'constraints' => array(
		    					'controller' => '[a-z-.]*',
		                        'action' => '[a-z-]*'
		                    ),
		                ),
						'child_routes'  => array(
							'wildcard' => array(
								'type' => 'wildcard',
							),
						),
    				)
    			)
            ),
        ),
    ),
);
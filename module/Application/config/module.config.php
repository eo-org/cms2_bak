<?php
return array(
	'controllers' => array(
        'invokables' => array(
            'Application\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Application\Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'user-defined' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[:resourceAlias][/:query].shtml',
                			'constraints' => array(
		            			'resourceAlias' => '[a-z]*',
                				'query' => '[a-z0-9-_]*'
		            		)
                        ),
                    ),
                    'article' => array(
                    	'type'    => 'Segment',
		            	'options' => array(
		            		'route' => 'article-[:id].shtml',
		            		'constraints' => array(
		            			'id' => '[a-z0-9]*',
		            		)
		            	)
		            ),
		            'list' => array(
		            	'type'    => 'Segment',
		            	'options' => array(
		            		'route' => 'list-[:id]/page[:page].shtml',
		            		'constraints' => array(
		            			'id' => '[a-z0-9]*',
		            			'page' => '[0-9]*'
		            		)
		            	)
		            ),
		            'product' => array(
		            	'type'    => 'Segment',
		            	'options' => array(
		            		'route' => 'product-[:id].shtml',
		            		'constraints' => array(
		            			'id' => '[a-z0-9]*',
		            		)
		            	)
		            ),
		            'product-list' => array(
		            	'type'    => 'Segment',
		            	'options' => array(
		            		'route' => 'product-list-[:id]/page[:page].shtml',
		            		'constraints' => array(
		            			'id' => '[a-z0-9]*',
		            			'page' => '[0-9]*'
		            		)
		            	)
		            ),
		            'search' => array(
		            	'type'    => 'Literal',
		            	'options' => array(
		            		'route' => 'search.shtml',
		            		'constraints' => array(
		            			
		            		)
		            	)
		            )
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
    		'layout/error'				=> __DIR__ . '/../view/layout/error.phtml',
            'layout/layout'				=> __DIR__ . '/../view/layout/layout.phtml',
    		'layout/head-client'		=> __DIR__ . '/../view/layout/head-client.phtml',
    		'layout/head-admin'			=> __DIR__ . '/../view/layout/head-admin.phtml',
    		'layout/toolbar'			=> __DIR__ . '/../view/layout/toolbar.phtml',
            'application/index/index'	=> __DIR__ . '/../view/application/index/index.phtml',
            'error/404'					=> __DIR__ . '/../view/error/404.phtml',
            'error/index'				=> __DIR__ . '/../view/error/index.phtml',
        ),
    ),
);

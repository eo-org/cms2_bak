<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'navi.json'			=> 'Rest\Controller\NaviController',
			'article.json'		=> 'Rest\Controller\ArticleController',
			'book.json'			=> 'Rest\Controller\BookController',
			'product-type.json'	=> 'Rest\Controller\ProductTypeController',
			'product.json'		=> 'Rest\Controller\ProductController',
			'ad-section.json'	=> 'Rest\Controller\AdSectionController',
			'ad.json'			=> 'Rest\Controller\AdController',
			'user.json'			=> 'Rest\Controller\UserController',
			'brick.json'		=> 'Rest\Controller\BrickController',
			'treeleaf.json'		=> 'Rest\Controller\TreeleafController',
			'head-file.json'	=> 'Rest\Controller\HeadFileController',
			'group.json'		=> 'Rest\Controller\GroupController',
			'layout.html'		=> 'Rest\Controller\LayoutController',
			'layout.json'		=> 'Rest\Controller\LayoutController',
			'domain.json'		=> 'Rest\Controller\DomainController'
		)
	),
	'router' => array(
		'routes' => array(
			'rest' => array(
				'type' => 'segment',
    			'options' => array(
    				'route' => '/rest'
    			),
    			'may_terminate' => true,
    			'child_routes' => array(
    				'restchildroutes' => array(
						'type' => 'segment',
						'options' => array(
							'route' => '[/:controller][/:id]',
							'constraints' => array(
								'controller' => '[a-z-.]*',
								'id' => '[a-z0-9.]*'
							)
						),
					),
					'actionchildroutes' => array(
						'type' => 'segment',
						'options' => array(
							'route' => '[/:controller][/:action]',
							'constraints' => array(
								'controller' => '[a-z-.]*',
								'action' => '[a-z-.]*',
							)
						),
					),
				)
			),
		),
	),
);
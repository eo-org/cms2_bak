<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'adminrest-navi'		=> 'Rest\Controller\NaviController',
			'adminrest-article'		=> 'Rest\Controller\ArticleController',
			'adminrest-book'		=> 'Rest\Controller\BookController',
			'adminrest-product-type'=> 'Rest\Controller\ProductTypeController',
			'adminrest-product'		=> 'Rest\Controller\ProductController',
			'adminrest-ad-section'	=> 'Rest\Controller\AdSectionController',
			'adminrest-ad'			=> 'Rest\Controller\AdController',
			'adminrest-user'		=> 'Rest\Controller\UserController',
			'adminrest-brick'		=> 'Rest\Controller\BrickController',
			'adminrest-treeleaf'	=> 'Rest\Controller\TreeleafController',
			'adminrest-head-file'	=> 'Rest\Controller\HeadFileController',
			'adminrest-group'		=> 'Rest\Controller\GroupController',
//			'adminrest-layout.html'	=> 'Rest\Controller\LayoutController',
			'adminrest-layout'		=> 'Rest\Controller\LayoutController',
			'adminrest-domain'		=> 'Rest\Controller\DomainController'
		)
	),
	'router' => array(
		'routes' => array(
			'adminrest' => array(
				'type' => 'literal',
    			'options' => array(
    				'route' => '/adminrest'
    			),
    			'may_terminate' => true,
    			'child_routes' => array(
    				'adminrest-childroutes' => array(
						'type' => 'segment',
						'options' => array(
							'route' => '[/:controller].[:format][/:id]',
							'constraints' => array(
								'controller' => '[a-z-]*',
								'format' => '(json|html)',
								'id' => '[a-z0-9]*'
							)
						),
					),
// 					'actionchildroutes' => array(
// 						'type' => 'segment',
// 						'options' => array(
// 							'route' => '[/:controller][/:action]',
// 							'constraints' => array(
// 								'controller' => '[a-z-.]*',
// 								'action' => '[a-z-.]*',
// 							)
// 						),
// 					),
				)
			),
		),
	),
	'view_manager' => array(
		'strategies' => array(
			'ViewJsonStrategy'
		),
	)
);
<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'site.json'			=> 'Rest\Controller\SiteController',
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
								'id' => '[a-z0-9]*'
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
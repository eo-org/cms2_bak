<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'site.api'			=> 'Api\Controller\SiteController',
		)
	),
	'router' => array(
		'routes' => array(
			'rest' => array(
				'type' => 'segment',
    			'options' => array(
    				'route' => '/api'
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
				)
			),
		),
	),
);
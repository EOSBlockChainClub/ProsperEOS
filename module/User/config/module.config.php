<?php
return array(
		'controllers' => array(
			'invokables' => array(
				'User\Controller\Index' => 'User\Controller\IndexController',
			),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'user' => __DIR__ . '/../view',
				),
		),
);

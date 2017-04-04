<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'UpdatePass\Controller\UpdatePassController' => 'UpdatePass\Controller\UpdatePassControllerController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'update-pass' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/updatePass',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'UpdatePass\Controller',
                        'controller'    => 'UpdatePassController',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'UpdatePass' => __DIR__ . '/../view',
        ),
    ),
	'di' => array(
			'services' => array(
					'Common\Model\UsersTable' => 'Common\Model\UsersTable'
			)
	),
 );

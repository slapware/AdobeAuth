<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file. Dev is 10.41.74.66 prod is 127.0.0.1
 */

return array(
    'db' => array(
    		'driver' => 'Pdo',
    		'dsn' => 'mysql:dbname=vendorid;host=?????????',
    		'driver_options' => array(
    				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
    				),
    		),
     'service_manager' => array(
         'factories' => array(
             'Zend\Db\Adapter\Adapter'
                     => 'Zend\Db\Adapter\AdapterServiceFactory',
         	),
         ),
		// Add our strategy to the view manager for our output
// 	    'view_manager' => array(
// 			'strategies' => array(
// 	            'ViewXmlStrategy',
// 	        ),
// 		),
	);

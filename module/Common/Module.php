<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Common for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Common;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Common\Model\User;
use Common\Model\UsersTable;
use Common\Model\Source;
use Common\Model\SourceTable;
use Common\Model\Order;
use Common\Model\OrderTable;
use Common\Model\Error;
use Common\Model\ErrorTable;
use Common\Model\WebUser;
use Common\Model\WebUserTable;
use Common\Model\Download;
use Common\Model\DownLoadView;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
    // Add this method:
    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    					'Common\Model\UsersTable' =>  function($sm) {
    						$tableGateway = $sm->get('UserTableGateway');
    						$table = new UsersTable($tableGateway);
    						return $table;
    					},
    					'UserTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new User());
    						return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Common\Model\SourceTable' =>  function($sm) {
    						$tableGateway = $sm->get('SourceTableGateway');
    						$table = new SourceTable($tableGateway);
    						return $table;
    					},
    					'SourceTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Source());
    						return new TableGateway('source', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Common\Model\OrderTable' =>  function($sm) {
    						$tableGateway = $sm->get('OrderTableGateway');
    						$table = new OrderTable($tableGateway);
    						return $table;
    					},
    					'OrderTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Order());
    						return new TableGateway('order', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Common\Model\ErrorTable' =>  function($sm) {
    						$tableGateway = $sm->get('ErrorTableGateway');
    						$table = new ErrorTable($tableGateway);
    						return $table;
    					},
    					'ErrorTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Error());
    						return new TableGateway('errors', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Common\Model\WebUserTable' =>  function($sm) {
    						$tableGateway = $sm->get('WebUserGateway');
    						$table = new WebUserTable($tableGateway);
    						return $table;
    					},
    					'WebUserGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new WebUser());
    						return new TableGateway('webuser', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Common\Model\DownLoadView' =>  function($sm) {
    						$tableGateway = $sm->get('DownLoadViewGateway');
    						$table = new DownLoadView($tableGateway);
    						return $table;
    					},
    					'DownLoadViewGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Download());
    						return new TableGateway('downloads', $dbAdapter, null, $resultSetPrototype);
    					},
    			),
    			'invokables' => array(
    					// Keys are the service names
    					// Values are valid class names to instantiate.
    					'AdobeID' => 'Common\Util\AdobeID',
    					'WebRegistration' => 'Common\Util\WebRegistration',
    			),
    			 
    	);
    }
    
}

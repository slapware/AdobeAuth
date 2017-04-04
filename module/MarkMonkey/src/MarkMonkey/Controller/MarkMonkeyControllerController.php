<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/MarkMonkey for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace MarkMonkey\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class MarkMonkeyControllerController extends AbstractActionController
{
    public function indexAction()
    {
		$uname = htmlspecialchars($this->getRequest()->getPost("username"));
		$upass = htmlspecialchars($this->getRequest()->getPost("password"));
		date_default_timezone_set('America/New_York');
		$udata = $uname . ":" . $upass;
    	$cookie = new  \Zend\Http\Header\SetCookie('hcreader', base64_encode($udata), time()+60*60*24*1825, "/", ".harpercollins.com"); // Zend\Http\Header\SetCookie instance
		$response = $this->getResponse()->getHeaders();
		$response->addHeader($cookie);    	
		return array();
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /markMonkeyController/mark-monkey-controller/foo
        return array();
    }
}

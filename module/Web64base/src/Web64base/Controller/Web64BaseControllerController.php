<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Web64base for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Web64base\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use XmlOutput\View\Model\XmlModel;

class Web64BaseControllerController extends AbstractActionController
{
	protected $webregister;
	
	protected $usersTable;
	protected $webUserTable;
	protected $sourceTable;

	 /**
     * Holds the Adobe uuid util
     *
     * @var AdobeID
     */
    protected $adbobeid;
    
    protected $messages;

    protected $xmltext;
        
    public function indexAction()
    {
    	if ('debug' == APPLICATION_ENV) {
    		$content = file_get_contents('/usr/local/zend/apache2/htdocs/AdobeAuth/web64_test.xml');
        	$data = $content;
    	} else {
    		$content = $this->getRequest()->getContent();
        	$data = base64_decode($content);
    	}
    	 
        $this->xmltext = trim($data, " \t\n\r\0\x0B" );
		$last = strrchr($data, '>');
		$slen = strlen($data);
        $this->parseInput();
        return new XmlModel(array(
		"message" => $this->messages,
		));
     }

     
     public function errorHandler($errno, $errstr, $errfile, $errline) {
     	$pos = strpos($errstr,"]:") ;
     	if ($pos) {
     		$errstr = substr($errstr,$pos+ 2);
     	}
     	$this->userInfo .="$errstr<br />\n";
     }
     
      
     public function parseInput() {
     	$webregister = $this->getWebReg();
     	$webregister->setXmlstring($this->xmltext);
     	$webregister->parseInput();
     	if ($webregister->getInError() == TRUE) {
		/* Other error handling */
		$errorTable = $this->getErrorTable();
    	$errorTable->create($webregister->getErroradd());
     		$this->messages = $webregister->getMessages(); 
     		return;
     	}
		// transaction start
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			
	     	$adbobeid = $this->getAdobeUtil();
	     	$uuid = $adbobeid->createUUID();
	     	$usersTable = $this->getUsersTable();
	     	$num = $usersTable->getUserCount($webregister->getUseradd()['email'], $webregister->getUseradd()['password']);
	     	if (!$num) {
	     		$found = 0;
	     	}
	     	else {
	     		$found = 1;
	     	}
	     	$lastInsertID = 0; // place holder for last inserted ID from MySql
	     	if ($found == 0) {
				$adbobeid = $this->getAdobeUtil();
				$uuid = $adbobeid->createUUID();
				$guid = array("uuid" => $uuid);
				$user = $webregister->getUseradd();
				$newuser = array_merge ($user, $guid);
	     		$lastInsertID = $usersTable->create($newuser);
	     	}
	     	else {
	     		$lastInsertID = $num->id;
	     	}
	     	$userid = $lastInsertID;
	     	$source = $webregister->getSourceadd();
			$userid = array("user_id" => $userid,);
			$newsoure = array_merge ($source, $userid);
	     	$sourceTable = $this->getSourceTable();
			$lastInsertID = $sourceTable->create($newsoure);
			$sourceid = array("source_id" => $lastInsertID,);
			$webuser = $webregister->getWebuseradd();
			$newWebuser = array_merge ($webuser, $userid, $sourceid);
			$webUserTable = $this->getWebUserTable();
			$id = $webUserTable->create($newWebuser);
			} // end try
		catch (\Exception $e) {
			if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
				$connection->rollback();
			}
		
		/* Other error handling */
		$errorTable = $this->getErrorTable();
    		$erroradd = array(
    				"from" => 'DR Register',
    				"data" => $this->xmltext,
    		);
    	$errorTable->create($erroradd);
		$message = '<?xml version="1.0" encoding=\"UTF-8\?>';
		$message .= '<registerRequest xmlns="http://ns.adobe.com/adept">';
		$message .= '<errpr xmlns="http://ns.adobe.com/adept" data="E_HCVID_REG Error adding />';
		$message .= '</registerRequest>';
		$xmlmsg = new \DOMDocument();
		$xmlmsg->preserveWhiteSpace = false;
		$xmlmsg->loadXML($message);
		$xmlmsg->formatOutput = true;
		$this->messages = $xmlmsg->saveXML();
		return;
		}	
		$this->messages = $webregister->getMessages();
     }
    /**
     * This is a convenience method to load the usersTable db object and keeps track
     * of the instance to avoid multiple of them
     *
     * @return UsersTable
     */
    protected function getUsersTable()
    {
    	if (!$this->usersTable) {
    		$sm = $this->getServiceLocator();
    		$this->usersTable = $sm->get('Common\Model\UsersTable');
    	}
    	return $this->usersTable;
    }
    protected function getSourceTable()
    {
    	if (!$this->sourceTable) {
    		$sm = $this->getServiceLocator();
    		$this->sourceTable = $sm->get('Common\Model\SourceTable');
    	}
    	return $this->sourceTable;
    }
    
    protected function getAdobeUtil()
    {
    	if (!$this->adbobeid) {
    		$sm = $this->getServiceLocator();
    		$this->adbobeid = $sm->get('Common\Util\AdobeID');
    	}
    	return $this->adbobeid;
    }
    protected function getWebReg()
    {
    	if (!$this->webregister) {
    		$sm = $this->getServiceLocator();
    		$this->webregister = $sm->get('Common\Util\WebRegistration');
    	}
    	return $this->webregister;
    }
    /**
     * This is a convenience method to load the webuserTable db object and keeps track
     * of the instance to avoid multiple of them
     *
     * @return webUserTable
     */
    protected function getWebUserTable()
    {
    	if (!$this->webUserTable) {
    		$sm = $this->getServiceLocator();
    		$this->webUserTable = $sm->get('Common\Model\WebUserTable');
    	}
    	return $this->webUserTable;
    }
    
    
    
    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /web64BaseController/web64-base-controller/foo
        return array();
    }
}

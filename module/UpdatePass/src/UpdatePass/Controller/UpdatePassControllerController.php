<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/UpdatePass for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace UpdatePass\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use XmlOutput\View\Model\XmlModel;
//use Zend\Db\Adapter\Adapter;

class UpdatePassControllerController extends AbstractActionController
{
    /**
     * Holds the user table object
     *
     * @var UsersTable
     */
    protected $usersTable;

	protected $messages;
    
    protected $dbAdapter;
	
    protected $errorTable;
    
	public function indexAction()
    {
    	if ('debug' == APPLICATION_ENV) {
			$content = "<updateRequest xmlns=\"http://ns.adobe.com/adept\">" .
  			"<username>guytos03@yahoo.com</username>" .
  			"<password>Winter20</password>" .
			"</updateRequest>";
        } else {
    		$content = $this->getRequest()->getContent();
    	}
    	
    	 
    $usersTable = $this->getUsersTable();
    $data = trim($content, " \t\n\r\0\x0B" );
    $xml = new \SimpleXmlElement($data);
	$dc = $xml->children('http://ns.adobe.com/adept');
	$email = $dc->username;
	$pass = $dc->password;
    		try {
				$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
				
    			$row = $usersTable->newUserPass($email, $pass);
 				$connection = $this->dbAdapter->getDriver()->getConnection();
     			$connection = $this->dbAdapter->getDriver()->getConnection();
     			$connection->beginTransaction();
    			} // end try
	catch (\Exception $e) {
		if ($connection instanceof \Zend\Db\Adapter\Driver\ConnectionInterface) {
			$connection->rollback();
		}
		$code = $e->getCode();
		$msg  = $e->getMessage();
		$line = $e->getLine();
		/* Other error handling */
		$errorTable = $this->getErrorTable();
    		$erroradd = array(
    				"from" => 'update Password',
    				"data" => $content,
    		);
    	$errorTable->create($erroradd);
		$message = '<?xml version="1.0" encoding=\"UTF-8"?>';
		$message .= '<updateResponce xmlns="http://ns.adobe.com/adept">';
		$message .= '<errpr xmlns="http://ns.adobe.com/adept" data="E_HCVID_REG not changed />';
		$message .= '</updateResponce>';
		$xmlmsg = new \DOMDocument();
		$xmlmsg->preserveWhiteSpace = false;
		$xmlmsg->loadXML($message);
		$xmlmsg->formatOutput = true;
		$this->messages = $xmlmsg->saveXML();
		return;
		}	
		if(!$row) {
			$message = '<?xml version="1.0" encoding="UTF-8"?>';
			$message .= '<updateResponce xmlns="http://ns.adobe.com/adept"> data="Failed"';
			$message .= '<error xmlns="http://ns.adobe.com/adept" data="E_HCVID_AUTH user not found"/>';
			$message .= '</updateResponce>';
			$xml = new \DOMDocument();
			$xml->preserveWhiteSpace = false;
			$xml->loadXML($message);
			$xml->formatOutput = true;
			$this->messages = $xml->saveXML();
    	}
    	else {
			$message = '<?xml version="1.0" encoding="UTF-8"?>';
			$message .= '<updateResponce xmlns="http://ns.adobe.com/adept"  data="Success">';
			$message .=  '<user>' . $row->email . '</user>';
			$message .=  "<new>" . $row->password . "</new>";
			$message .= '</updateResponce>';
			$xml = new \DOMDocument();
			$xml->preserveWhiteSpace = false;
			$xml->loadXML($message);
			$xml->formatOutput = true;
			$this->messages = $xml->saveXML();
    	}
        return new XmlModel(array(
		"message" => $this->messages,
		));
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
    
    protected function getErrorTable()
    {
    	if (!$this->errorTable) {
    		$sm = $this->getServiceLocator();
    		$this->errorTable = $sm->get('Common\Model\ErrorTable');
    	}
    	return $this->errorTable;
    }
        
        public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /updatePassController/update-pass-controller/foo
        return array();
    }
}

<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/SignIn for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace SignIn\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use XmlOutput\View\Model\XmlModel;

class SignInControllerController extends AbstractActionController
{
    /**
     * Holds the user table object
     *
     * @var UsersTable
     */
    protected $usersTable;

	protected $messages;
    
    protected $dbAdapter;
	
	public function indexAction()
    {
    	if ('debug' == APPLICATION_ENV) {
			$content = "<signInRequest xmlns=\"http://ns.adobe.com/adept\">" .
  			"<username>guytos03@yahoo.com</username>" .
  			"<password>Winter14</password>" .
			"</signInRequest>";
    	} else {
    		$content = $this->getRequest()->getContent();
    	}
    	 
        $xml = new \SimpleXmlElement($content);
		$dc = $xml->children('http://ns.adobe.com/adept');
        $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
 		$stmt = $this->dbAdapter->createStatement();
		$stmt->prepare('CALL SignIn(?, ?)');
		$stmt->getResource()->bindParam(1, $dc->username);
		$stmt->getResource()->bindParam(2, $dc->password, \PDO::PARAM_INT, 2);
		$result = $stmt->execute();
		$row = $result->current();
		if(!$row) {
			$message = '<?xml version="1.0" encoding="UTF-8"?>';
			$message .= '<signInResponce xmlns="http://ns.adobe.com/adept">';
			$message .= '<error xmlns="http://ns.adobe.com/adept" data="E_HCVID_AUTH user not found"/>';
			$message .= '</signInResponce>';
			$xml = new \DOMDocument();
			$xml->preserveWhiteSpace = false;
			$xml->loadXML($message);
			$xml->formatOutput = true;
			$this->messages = $xml->saveXML();
    	}
    	else {
			$message = '<?xml version="1.0" encoding="UTF-8"?>';
			$message .= '<signInResponce xmlns="http://ns.adobe.com/adept">';
			$message .=  '<user>' . $row['uuid'] . '</user>';
			$message .=  "<label>" . $row['email'] . "</label>";
			$message .= '</signInResponce>';
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

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /signInController/sign-in-controller/foo
        return array();
    }
}

<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Account for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Account\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use XmlOutput\View\Model\XmlModel;

class AccountInfoControllerController extends AbstractActionController
{
    /**
     * Holds the user table object
     *
     * @var UsersTable
     */
    protected $usersTable;

	protected $messages;
    
    public function indexAction()
    {
        $content = $this->getRequest()->getContent();
        $xml = new \SimpleXmlElement($content);
		$dc = $xml->children('http://ns.adobe.com/adept');
		// TableGateway load section
     	$usersTable = $this->getUsersTable();
		$result = $usersTable->getInfo($dc->user);
        if (!$result) {
			$message = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
			$message .= '<accoutInfoResponce xmlns="http://ns.adobe.com/adept">';
			$message .= '<error xmlns="http://ns.adobe.com/adept" data="E_HCVID_ACCOUNT_INFO User not found."/>';
			$message .= '</accoutInfoResponce>';
        	$this->messages = 'User not found';
        	    	}
    	else {
			$message = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
			$message .= '<accoutInfoResponce method="standard" xmlns="http://ns.adobe.com/adept">';
			$message .= "<label>" . $this->scramble($result->email) . "</label>";
			$message .= '</accoutInfoResponce>';
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

    function scramble($email) {
    	$raw = $email;
    	$findme = '@';
    	$pos = strpos ( $raw, '@' );
    	if ($pos !== false) {
    		if ($pos > 6) {
    			$raw = substr_replace ( $raw, '*****', $pos - 5, 5 );
    		} else {
    			$raw = substr_replace ( $raw, '**', 1, 2 );
    		}
    	} // if ($pos !== false)
    	return $raw;
    } // scramble
    
    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /accountInfoController/account-info-controller/foo
        return array();
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
}

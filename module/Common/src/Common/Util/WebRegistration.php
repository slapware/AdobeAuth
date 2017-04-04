<?php

namespace Common\Util;

class WebRegistration {
	protected $adbobeid;
	/**
	 * @var error storage
	 */
	protected $error;
	/**
	 * @var bool error flag.
	 */
	protected $inError;
	/**
	 * @var The xml string.
	 */
	protected $xmlstring;
	/**
	 * @var return messages.
	 */
	protected $messages;
	/**
	 * User
	 * @var array() $useradd
	 * This is user input data.
	 */
	protected $useradd = array();
	/**
	 * Source
	 * @var array() $useradd
	 * This is user input data.
	 */
	protected $sourceadd = array();
	/**
	 * Web Extra data
	 * @var array() $useradd
	 * This is user input data.
	 */
	protected $webuseradd = array();
	/**
	 * Error data
	 * @var array() $erroradd
	 * This is error input data.
	 */
	protected $erroradd = array();
	/**
	 * @return the $erroradd
	 */
	public function getErroradd() {
		return $this->erroradd;
	}

	/**
	 * @param \Common\Util\array() $erroradd
	 */
	public function setErroradd($erroradd) {
		$this->erroradd = $erroradd;
	}

	/**
	 * @return the $error
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * @return the $inError
	 */
	public function getInError() {
		return $this->inError;
	}

	/**
	 * @return the $xmlstring
	 */
	public function getXmlstring() {
		return $this->xmlstring;
	}

	/**
	 * @return the $messages
	 */
	public function getMessages() {
		return $this->messages;
	}

	/**
	 * @return the $useradd
	 */
	public function getUseradd() {
		return $this->useradd;
	}

	/**
	 * @return the $sourceadd
	 */
	public function getSourceadd() {
		return $this->sourceadd;
	}

	/**
	 * @return the $webuseradd
	 */
	public function getWebuseradd() {
		return $this->webuseradd;
	}

	/**
	 * @param \Common\Util\error $error
	 */
	public function setError($error) {
		$this->error = $error;
	}

	/**
	 * @param boolean $inError
	 */
	public function setInError($inError) {
		$this->inError = $inError;
	}

	/**
	 * @param \Common\Util\The $xmlstring
	 */
	public function setXmlstring($xmlstring) {
		$this->xmlstring = $xmlstring;
	}

	/**
	 * @param \Common\Util\return $messages
	 */
	public function setMessages($messages) {
		$this->messages = $messages;
	}

	/**
	 * @param \Common\Util\array() $useradd
	 */
	public function setUseradd($useradd) {
		$this->useradd = $useradd;
	}

	/**
	 * @param \Common\Util\array() $sourceadd
	 */
	public function setSourceadd($sourceadd) {
		$this->sourceadd = $sourceadd;
	}

	/**
	 * @param \Common\Util\array() $webuseradd
	 */
	public function setWebuseradd($webuseradd) {
		$this->webuseradd = $webuseradd;
	}

	function __construct() {
//		$this->setXmlstring($xml);
		$this->setInError(FALSE);
	}
	
	function __destruct() {
	}
	
	public function parseInput() {
    	// disable errors
    	libxml_use_internal_errors(true);
    
    	$xml = new \DOMDocument();
    	// We don't want to bother with white spaces
    	$xml->preserveWhiteSpace = false;
		$xml->validateOnParse = true;
    	try {
		$res = $xml->loadXML($this->xmlstring);
    	} catch (\Exception $e) {
  			$xml = null;
		}    	
    	if(!$res)
    	{
    		$this->erroradd = array(
    				"from" => "Web base64 reg",
    				"data" => $this->xmlstring,
    				);
    		$this->error = 'Error reading XML';
    		$this->inError = TRUE;
    		return;
    	}
    	$xpath = new \DOMXPath($xml);
    	$orderid = $xml->getElementsByTagName("orderID")->item(0)->nodeValue;
//    	restore_error_handler();
    	 
    	$submissionDate = $xml->getElementsByTagName("submissionDate")->item(0)->nodeValue;
    	$title = $xml->getElementsByTagName("title")->item(0)->nodeValue;
    	//echo $ns1->submissionDate;
    	$result = $xpath->query("//site/siteAddress/addressID");
    	$addressID = $result->item(0)->nodeValue;
    	$result = $xpath->query('//site/siteAddress/city');
    	$city = $result->item(0)->nodeValue;
    	$result = $xpath->query('//site/siteAddress/country');
    	$country = $result->item(0)->nodeValue;
    	$result = $xpath->query('//site/siteAddress/countryName');
    	$countryName = $result->item(0)->nodeValue;
    	$result = $xpath->query('//site/siteAddress/line1');
    	$line1 = $result->item(0)->nodeValue;
    	$result = $xpath->query('//site/siteAddress/name1');
    	$name1 = $result->item(0)->nodeValue;
    	$result = $xpath->query('//site/siteAddress/name2');
    	$name2 = $result->item(0)->nodeValue;
    	$result = $xpath->query('//site/siteAddress/phoneNumber');
    	$phoneNumber = $result->item(0)->nodeValue;
    	$result = $xpath->query('//site/siteAddress/postalCode');
    	$postalCode = $result->item(0)->nodeValue;
    	$result = $xpath->query('//site/siteAddress/state');
    	$state = $result->item(0)->nodeValue;
    	//
     	$result = $xpath->query('//firstname');
		if (!isset($result))
		{
			$FirstName = "na";
		}
		else {
			$FirstName = $result->item(0)->nodeValue;
		}
		
		$result = $xpath->query('//lastname');
		if (!isset($result) )
		{
			$LastName = "na";
		}
		else {
			$LastName = $result->item(0)->nodeValue;
		} 
		//
		$result = $xpath->query('//region');
		if (!isset($result))
		{
			$Region = "na";
		}
		else {
			$Region = $result->item(0)->nodeValue;
		} 
		
    	$result = $xpath->query('//loginID');
		$loginID = $result->item(0)->nodeValue;
		$result = $xpath->query('//password');
		$Password = $result->item(0)->nodeValue;
		$this->useradd = array(
				"email" => $loginID,
				"password" => md5($Password),
				"joindate" => "",
		);
		// transactio start
		try {
// 			$connection = $this->dbAdapter->getDriver()->getConnection();
// 			$connection->beginTransaction();
	
		$this->sourceadd = array(
				"orderid" => $orderid,
				"submissionDate"	=> $submissionDate,
				"type" => $title,
				"city" => $city,
				"address1"	=> $line1,
				"site_name1" => $name1,
				"site_name2"	=> $name2,
				"site_phone" => $phoneNumber,
				"postalCode" => $postalCode,
				"state"	=> $state,
		);
		$this->webuseradd = array(
//				"source_id" => $userid,
//				"user_id" => $userid,
				"firstname"	=> $FirstName,
				"lastname" => $LastName,
				"region" => $Region,
		);
	} // end try
	catch (\Exception $e) {
    		$erroradd = array(
    				"from" => 'DR Register',
    				"data" => $this->xmltext,
    		);
    	$this->error = 'Error reading XML';
    	$this->inError = TRUE;
		$message = '<?xml version="1.0" encoding=\"UTF-8\?>';
		$message .= '<registerRequest xmlns="http://ns.adobe.com/adept">';
		$message .= '<errpr xmlns="http://ns.adobe.com/adept" data="E_HCVID_REG not found />';
		$message .= '</registerRequest>';
		$xmlmsg = new \DOMDocument();
		$xmlmsg->preserveWhiteSpace = false;
		$xmlmsg->loadXML($message);
		$xmlmsg->formatOutput = true;
		$this->messages = $xmlmsg->saveXML();
		return;
		}	
	
		$message = '<?xml version="1.0" encoding="UTF-8"?>';
		$message .= '<registerRequest xmlns="http://ns.adobe.com/adept">';
		$message .= "<Success>Registered</Success>";
		$message .= '</registerRequest>';
		$xmlmsg = new \DOMDocument();
		$xmlmsg->preserveWhiteSpace = false;
		$xmlmsg->loadXML($message);
		$xmlmsg->formatOutput = true;
		$this->messages = $xmlmsg->saveXML();
		return;
    }
    
      
}

?>
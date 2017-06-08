<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/DigitalRiver for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace DigitalRiver\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use XmlOutput\View\Model\XmlModel;
//use Zend\Validator;

class DROrderControllerController extends AbstractActionController
{
    /**
     * Holds the user table object
     *
     * @var UsersTable
     */
    protected $usersTable;
    /**
     * Holds the source table object
     *
     * @var SourceTable
     */
    protected $sourceTable;
    /**
     * Holds the order table object
     *
     * @var OrderTable
     */
    protected $orderTable;
    /**
     * Holds the error table object
     *
     * @var ErrorTable
     */
    protected $errorTable;
    
    protected $adbobeid;
    
    protected $dbAdapter;

    protected $messages;

    protected $xmltext;
    
	public function indexAction()
    {
//		$requestHeaders  = $this->getRequest()->getHeaders();
    	$validator = new \Zend\Validator\File\MimeType('application/vnd.adobe.adept+xml');
    	if ('debug' == APPLICATION_ENV) {
    	$content = file_get_contents('/usr/local/zend/apache2/htdocs/AdobeAuth/encrypt-digital-order-with-URL.xml');
		} else {
        	$content = $this->getRequest()->getContent();
		}
        $data = $this->getDecrypt($content, 'Vta49KVHOi5x????', 'Vta49KVHOi5x????');
		$this->xmltext = trim($data, " \t\n\r\0\x0B" );
		$last = strrchr($data, '>');
		$slen = strlen($data);
        $this->parseInput();
//        $this->parseInput(trim($data));
        return new XmlModel(array(
		"message" => $this->messages,
		));
     }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /dROrderController/d-r-order-controller/foo
        return array();
    }
    
    public function getDecrypt($sStr, $sKey, $sIV) {
    	return mcrypt_decrypt(
    			MCRYPT_RIJNDAEL_128,
    			$sKey,
    			base64_decode($sStr),
    			MCRYPT_MODE_CBC,
    			$sIV
    	);
    }
    
    public function errorHandler($errno, $errstr, $errfile, $errline) {
    	$pos = strpos($errstr,"]:") ;
    	if ($pos) {
    		$errstr = substr($errstr,$pos+ 2);
    	}
    	$this->userInfo .="$errstr<br />\n";
    }
    
    public function parseInput() {
    	// disable errors
    	libxml_use_internal_errors(true);
    
        set_error_handler(array($this,"errorHandler"));
    	$xml = new \DOMDocument();
    	// We don't want to bother with white spaces
    	$xml->preserveWhiteSpace = false;
		$xml->validateOnParse = true;
    	try {
		$res = $xml->loadXML($this->xmltext);
    	} catch (\Exception $e) {
  			$xml = null;
		}    	
    	if(!$res)
    	{
        	$errorTable = $this->getErrorTable();
    		$erroradd = array(
    				"from" => "DR Order",
    				"data" => $this->xmltext,
    				);
    		$id = $errorTable->create($erroradd);
    		$this->messages = 'Error reading XML';
    		return;
    	}
    	$xpath = new \DOMXPath($xml);
    	$orderid = $xml->getElementsByTagName("orderID")->item(0)->nodeValue;
    	restore_error_handler();
    	 
    	$submissionDate = $xml->getElementsByTagName("submissionDate")->item(0)->nodeValue;
    	$title = $xml->getElementsByTagName("title")->item(0)->nodeValue;
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
    	$result = $xpath->query('//pricing/total/currencyCode');
    	if (!isset($result))
    	{
    	$currencyCode = "na";
    	}
    	else {
    		$currencyCode = $result->item(0)->nodeValue;
    	}
    	$result = $xpath->query('//pricing/total/amount');
    	if (!isset($result) )
    	{
    		$amount = "0.0";
    		$err = $orderid . " has blank amount.";
    		error_log($err);
    }
    	else {
    	$amount = $result->item(0)->nodeValue;
    }
    //
    $result = $xpath->query('//pricing/subtotal/amount');
	$count = $result->length;
	if ($count == 0)
	{
		$amount = "0.0";
		$err = $orderid . " has blank sub total.";
		error_log($err);
	}
	else {
		$subtotal = $result->item(0)->nodeValue;
	}
		
    $result = $xpath->query('//paymentInfos/paymentInfo/customerEmail');
    if (!isset($result))
    {
    	$customerEmail = "na";
    	$err = $orderid . " has blank cutomer email.";
    	error_log($err);
    }
    else {
    	$customerEmail = $result->item(0)->nodeValue;
    }
    $result = $xpath->query('//paymentInfos/paymentInfo/customerLastName');
    if (!isset($result))
    {
    	$customerLastName = "na";
    	$err = $orderid . " has blank customer last name.";
    	error_log($err);
    }
    else {
    	$customerLastName = $result->item(0)->nodeValue;
    }
    $result = $xpath->query('//paymentInfos/paymentInfo/customerFirstName');
    if (!isset($result))
    {
    	$customerFirstName = "na";
    	$err = $orderid . " has blank customer first name.";
    	error_log($err);
    }
    else {
    	$customerFirstName = $result->item(0)->nodeValue;
    }
    $result = $xpath->query('//paymentInfos/paymentInfo/paymentMethodName');
    if (!isset($result))
    {
    	$paymentMethodName = "na";
    	$err = $orderid . " has blank payment method.";
    	error_log($err);
    }
    else {
    	$paymentMethodName = $result->item(0)->nodeValue;
    }
    $result = $xpath->query('//paymentInfos/paymentInfo/billingAddress/postalCode');
    if (!isset($result))
    {
    	$billpostalCode = "na";
    	$err = $orderid . " has blank billing zip code.";
    	error_log($err);
    }
    else {
    	$billpostalCode = $result->item(0)->nodeValue;
    };
    $result = $xpath->query('//paymentInfos/paymentInfo/billingAddress/city');
    if (!isset($result))
    {
    	$billcity = "na";
    	$err = $orderid . " has blank billing city.";
    	error_log($err);
    }
    else {
    	$billcity = $result->item(0)->nodeValue;
    }
    
		// Check if test order
		$result = $xpath->query('//testOrder');
		$testorder = $result->item(0)->nodeValue;
    
// 		$result = $xpath->query('//lineItems/item/lineItemID');
//     		if (!isset($result) OR empty($result))
//     		{
//     		$lineItemID = "na";
//     				$err = $orderid . " has blank line item.";
//     						error_log($err);
//     		}
//     		else {
//     		$lineItemID = $result->item(0)->nodeValue;
//     		}
//     		$result = $xpath->query('//lineItems/item/product/productID');
//     		if (!isset($result))
//     		{
//     		$productID = "na";
//     				$err = $orderid . " has blank ProductID.";
//     						error_log($err);
//     		}
//     		else {
//     			$productID = $result->item(0)->nodeValue;
//     		}
//     		$result = $xpath->query('//lineItems/item/product/externalReferenceID');
//     		if (!isset($result))
//     		{
//     			$externalReferenceID = "na";
//     			$err = $orderid . " has blank ISBN.";
//     			error_log($err);
//     		}
//     		else {
//     			$externalReferenceID = $result->item(0)->nodeValue;
//     		}
    		$result = $xpath->query('//loginID');
    		$loginID = $result->item(0)->nodeValue;
    		// lets validate the email address here
    		$result = $xpath->query('//password');
    		$Password = $result->item(0)->nodeValue;
    		// Addition for multiple ACSM urls
    		$xpath->registerNamespace("xsi","http://www.w3.org/2001/XMLSchema-instance");
   			$nodes = $xpath->query("//lineItems/item/extendedAttributes/item[contains(value, 'http://delivery.dscebook.digiplug.com')]");
			$orderArray = array();
			$temp_dom = new \DOMDocument();
			foreach($nodes as $n) $temp_dom->appendChild($temp_dom->importNode($n,true));
			$elements = $temp_dom->getElementsByTagName("value");
			foreach ($elements as $element) {
				array_push($orderArray, $element->nodeValue);
			}
			// TableGateway load section
     		$usersTable = $this->getUsersTable();
     		$sourceTable = $this->getSourceTable();
     		$orderTable = $this->getOrderTable();
     		// get Util class for Adobe ID
     		$adbobeid = $this->getAdobeUtil();
     		$uuid = $adbobeid->createUUID();
     		try {
    		$num = $usersTable->getUserCount($loginID, $Password);
    		} // end try
    		catch (\Exception $e) {
    			$message = '<?xml version="1.0" encoding="UTF-8"?>';
    			$message .= '<registerRequest xmlns="http://ns.adobe.com/adept">';
    			$message .= '<errpr xmlns="http://ns.adobe.com/adept" data="database is not reachable."/>';
    			$message .= '</registerRequest>';
    			$xmlmsg = new \DOMDocument();
    			$xmlmsg->preserveWhiteSpace = false;
    			$xmlmsg->loadXML($message);
    			$xmlmsg->formatOutput = true;
    			$this->messages = $xmlmsg->saveXML();
    			return;
    		}
    		
    		if (!$num) {
    			$found = 0;
    		}
    		else {
    			$found = 1;
    		}
			$lastInsertID = 0; // place holder for last inserted ID from MySql
    		$this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
    		$useradd = array(
    				"email" => $loginID,
    				"password" => $Password,
    				"uuid"	=> $uuid,
    				"joindate" => "",
    		);
    		if ($found == 0) {
				$lastInsertID = $usersTable->create($useradd);
    		}
    		else {
    			$lastInsertID = $num->id;
    		}
    		// transactio start
    		try {
     			$connection = $this->dbAdapter->getDriver()->getConnection();
    			$connection->beginTransaction();
   		
    		$sourceadd = array(
    				"user_id" => $lastInsertID,
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
    			$userid = $lastInsertID;
    			$lastInsertID = $sourceTable->create($sourceadd);
    			// test new lineitem fetch here
    			// NOTE: Major update start here -----------------------
    			$lineitem_nodes = $xpath->query('//lineItems/*');
    			$li = $lineitem_nodes->length;
    			foreach ($lineitem_nodes as $node) {
    				// do something with this node
    				$lineItemID = $node->getElementsByTagName("lineItemID")->item(0)->nodeValue;
    				$quantity = $node->getElementsByTagName("quantity")->item(0)->nodeValue;
    				$externalReference = $node->getElementsByTagName("externalReferenceID")->item(1)->nodeValue;
    				$title = $node->getElementsByTagName("name")->item(0)->nodeValue;
    				$source = $node->getElementsByTagName("unitPrice")->item(0)->nodeValue;
    				$unitPrice = preg_replace( '/[^0-9.]/', '', $source );
    			
    				$result = $xpath->query('.//pricing/shipping/amount', $node);
    				$count = $result->length;
    				if ($count == 0)
    				{
    					$shipping = "0.0";
    				}
    				else {
    					$shipping = $result->item(0)->nodeValue;
    				}
    				$productID = $node->getElementsByTagName("productID")->item(0)->nodeValue;
    				$mfrPartNumber = $node->getElementsByTagName("mfrPartNumber")->item(0)->nodeValue;
    				$postalcode = $node->getElementsByTagName("postalCode")->item(0)->nodeValue;
    				// NOTE: Get the title format here
    				$formats = $xpath->query('.//productInfo/extendedAttributes/*', $node);
    				$lf = $formats->length;
    				$format = "";
    				foreach ($formats as $fnode) {
    					$test = $fnode->getElementsByTagName("name")->item(0)->nodeValue;
    					if($test == "Format") {
    						$format = $fnode->getElementsByTagName("value")->item(0)->nodeValue;
    						break;
    					}
    				}
    				$store = 'na';
    				$findstore = $xpath->query('./extendedAttributes/*', $node);
    				$ls = $findstore->length;
    				foreach ($findstore as $snode) {
    					$test = $snode->getElementsByTagName("name")->item(0)->nodeValue;
    					if($test == "hcstoreID") {
    						$store = $snode->getElementsByTagName("value")->item(0)->nodeValue;
    						break;
    					}
    				}
    				$findurl = $xpath->query('./extendedAttributes/*', $node);
    				if (strpos($format,'DRM') !== false OR strpos($format, 'Encrypted') !== false OR strpos($format, 'eBook') !== false) {
    					foreach ($findurl as $unode) {
    						$test = $unode->getElementsByTagName("name")->item(0)->nodeValue;
    						if($test == "DGPDownloadURL") {
    							$url = $unode->getElementsByTagName("value")->item(0)->nodeValue;
    							break;
    						}
    					}
    				}
    				else {
    					$url = $format;
    				}
    				// NOTE: perform DB insert here
    				$orderdata = array(
    				"user_id" => $userid,
    				"source_id" => $lastInsertID,
    				"currencyCode" => $currencyCode,
    				"amount" => $amount,
    				"custemail" => $customerEmail,
    				"custlastname"	=> $customerLastName,
    				"custfirstname" => $customerFirstName,
    				"paymethod" => $paymentMethodName,
    				"postalcode"	=> $billpostalCode,
    				"city" => $billcity,
    				"lineitem"  => $lineItemID,
    				"productid"  => $productID,
    				"isbn"  => $externalReference,
    				"unitprice"  => $unitPrice, // new
    				"storeid"  => $store,   // new
    				"url"  => $url,		// new
    				"mfrPartNumber"  => $mfrPartNumber,
    				
//     						"orderID" => $orderid,
//     						"submissionDate" => $submissionDate,
//     						"title" => $title,
//     						"city" => $city,
//     						"line1" => $line1,
//     						"name1" => $name1,
//     						"name2" => $name2,
//     						"phoneNumber" => $phoneNumber,
//     						"currencyCode" => $currencyCode,
//     						"postalCode" => $postalCode,
//     						"state" => $state,
//     						"totalamount" => $amount,
//     						"subtotal" => $subtotal,
//     						"customerEmail" => $customerEmail,
//     						"customerLastName" => $customerLastName,
//     						"customerFirstName" => $customerFirstName,
//     						"paymentMethodName" => $paymentMethodName,
//     						"billpostalCode" => $billpostalCode,
//     						"billcity" => $billcity,
//     						"lineItemID" => $lineItemID,
//     						"productID" => $productID,
//     						"externalReferenceID" => $externalReference,
//     						"loginID" => $loginID,
//     						"Password" => $Password,
//     						"unitPrice" => $unitPrice,
//     						"quantity" => $quantity,
//     						"shipping" => $shipping,
//     						"testorder" => $testorder,
//     						"postalcode" => $postalcode,
//     						"store" => $store,
//     						"url" => $url,
//     						"mfrPartNumber" => $mfrPartNumber,
    				);
    					$orderTable->create($orderdata);
    			}
    			// old code below here
//     			$items = $xpath->query('//lineItems/item');
//     			$item_count = $items->length;
//     			foreach ($items as $item) {
//     				$orderdata = array(
//     				"user_id" => $userid,
//     				"source_id" => $lastInsertID,
//     				"currencyCode" => $currencyCode,
//     				"amount" => $amount,
//     				"custemail" => $customerEmail,
//     				"custlastname"	=> $customerLastName,
//     				"custfirstname" => $customerFirstName,
//     				"paymethod" => $paymentMethodName,
//     				"postalcode"	=> $billpostalCode,
//     				"city" => $billcity,
//     				"lineitem"  => $item->getElementsByTagName('lineItemID')->item(0)->nodeValue,
//     				"productid"  => $item->getElementsByTagName('productID')->item(0)->nodeValue,
//     				"isbn"  => $item->getElementsByTagName('externalReferenceID')->item(1)->nodeValue,
//     				"unitprice"  => $item->getElementsByTagName('amount')->item(0)->nodeValue, // new
//     				"storeid"  => $item->getElementsByTagName('value')->item(11)->nodeValue,   // new
//     				"url"  => $item->getElementsByTagName('value')->item(12)->nodeValue,		// new
//     				"mfrPartNumber"  => $item->getElementsByTagName('mfrPartNumber')->item(0)->nodeValue,
//     				);
//     			$orderTable->create($orderdata);
//    			}
    		$connection->commit();
    			
    		// make array of data
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
		$message = '<?xml version="1.0" encoding=\"UTF-8"?>';
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
    		
    } // parseXml
    
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
    protected function getOrderTable()
    {
    	if (!$this->orderTable) {
    		$sm = $this->getServiceLocator();
    		$this->orderTable = $sm->get('Common\Model\OrderTable');
    	}
    	return $this->orderTable;
    }
    protected function getAdobeUtil()
    {
    	if (!$this->adbobeid) {
    		$sm = $this->getServiceLocator();
    		$this->adbobeid = $sm->get('Common\Util\AdobeID');
    	}
    	return $this->adbobeid;
    }
    protected function getErrorTable()
    {
    	if (!$this->errorTable) {
    		$sm = $this->getServiceLocator();
    		$this->errorTable = $sm->get('Common\Model\ErrorTable');
    	}
    	return $this->errorTable;
    }
}

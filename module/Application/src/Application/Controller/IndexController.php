<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
//use Zend\View\Model\ViewModel;
use XmlOutput\View\Model\XmlModel;
//use Zend\Crypt\BlockCipher;
//Zend\Crypt\Symmetric\Mcrypt;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
		$message = '<?xml version="1.0" encoding=\"UTF-8"?>';
		$message .= '<registerRequest xmlns="http://ns.adobe.com/adept">';
		$message .= '<errpr xmlns="http://ns.adobe.com/adept" data="E_HCVID_REG this service is no longer available. />';
		$message .= '</registerRequest>';
		$xmlmsg = new \DOMDocument();
		$xmlmsg->preserveWhiteSpace = false;
		$xmlmsg->loadXML($message);
		$xmlmsg->formatOutput = true;
		$msg = $xmlmsg->saveXML();
        return new XmlModel(array(
		"message" => $msg,
		));
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
}

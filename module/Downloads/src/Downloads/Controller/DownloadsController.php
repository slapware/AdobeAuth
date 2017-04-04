<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Downloads for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Downloads\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use XmlOutput\View\Model\XmlModel;

class DownloadsController extends AbstractActionController
{
	protected $downloadView;
	
	protected $messages;
	
    public function indexAction()
    {
	if ('debug' == APPLICATION_ENV) {
    	$content = "<urlRequest xmlns=\"http://ns.adobe.com/adept\">" .
  		"<username>guytos03@yahoo.com</username>" .
		"</ur;Request>";
    	} else {
    		$content = $this->getRequest()->getContent();
    	}
    	 
        $xml = new \SimpleXmlElement($content);
		$dc = $xml->children('http://ns.adobe.com/adept');
        $downloadView = $this->getDownloadView();
		$urls = $downloadView->getByUsername($dc->username);
		$message = '<?xml version="1.0" encoding="UTF-8"?>';
		$message .= '<downloadResponce xmlns="http://ns.adobe.com/adept">';
		foreach($urls as $url) {
			$message .=  "<url>";
			$message .=  "<email>" . $url->email . "</email>";
			$message .=  '<first>' . $url->custfirstname . '</first>';
			$message .=  '<last>' . $url->custlastname . '</last>';
			$message .=  '<isbn>' . $url->isbn . '</isbn>';
			$message .=  '<store>' . $url->storeid . '</store>';
			$message .=  '<price>' . $url->unitprice . '</price>';
			$message .=  '<url>' . $url->url . '</url>';
			$message .=  "</url>";
		}
		$message .= '</downloadResponce>';
		$this->messages = $message;
		return new XmlModel(array(
		"message" => $this->messages,
		));
	}

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /downloadsController/downloads-controller/foo
        return array();
    }
    
    protected function getDownloadView()
    {
    	if (!$this->downloadView) {
    		$sm = $this->getServiceLocator();
    		$this->downloadView = $sm->get('Common\Model\DownLoadView');
    	}
    	return $this->downloadView;
    }
    
}

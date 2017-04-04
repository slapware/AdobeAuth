<?php

namespace Common\Util;

class AdobeID {
	/**
	 * Adobe Node Value len = 12
	 * @var string $adobenode
	 * This is input for Vendor ID.
	 */
	protected $adobenode;
	
	/**
	 * @return the $adobenode
	 */
	public function getAdobenode() {
		return $this->adobenode;
	}

	/**
	 * @param string $adobenode
	 */
	public function setAdobenode($adobenode) {
		$this->adobenode = $adobenode;
	}
	
	function __construct() {
		$this->setAdobenode("e546a16b291d");
	}

	function __destruct() {
	}
	/*  
	 * Create the unique Adobe vendor ID
	 */
	public function createUUID() {
		// Time based PHP Unique ID
		$uid = uniqid(NULL, TRUE);
		// Random SHA1 hash
		$rawid = strtoupper(sha1(uniqid(rand(), true)));
		// Produce the results
		$result = substr($uid, 6, 8);
		$result .= substr($uid, 0, 4);
		$result .= substr(sha1(substr($uid, 3, 3)), 0, 4);
		$result .= substr(sha1(substr(time(), 3, 4)), 0, 4);
		$result .= strtolower(substr($rawid, 10, 12));
		$result[0] = '0';
		$result[12] = '1';
		$result = substr_replace($result, $this->getAdobenode(), -12);
		$result = substr_replace($result, '-', 8, 0);
		$result = substr_replace($result, '-', 13, 0);
		$result = substr_replace($result, '-', 18, 0);
		$result = substr_replace($result, '-', 23, 0);
		// Return the result
		return "urn:uuid:" . $result;
	}
	
}

?>
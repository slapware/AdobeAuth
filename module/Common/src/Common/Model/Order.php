<?php
namespace Common\Model;

class Order
{
	public $id;
	public $source_id;
	public $user_id;
	public $currencyCode;
	public $amount;
	public $subtotal;
	public $custemail;
	public $custlastname;
	public $custfirstname;
	public $paymethod;
	public $testorder;
	public $postalcode;
	public $city;
	public $lineitem;
	public $productid;
	public $isbn;
	public $orderdate;
	public $url;
	public $unitprice;
	public $storeid;
	public $mfrPartNumber;
	
	public function exchangeArray($data)
	{
		$this->id     = (isset($data['id'])) ? $data['id'] : null;
		$this->source_id     = (isset($data['source_id'])) ? $data['source_id'] : null;
		$this->user_id     = (isset($data['user_id'])) ? $data['user_id'] : null;
		$this->currencyCode = (isset($data['currencyCode'])) ? $data['currencyCode'] : null;
		$this->amount  = (isset($data['amount'])) ? $data['amount'] : null;
		$this->subtotal  = (isset($data['subtotal'])) ? $data['subtotal'] : null;
		$this->custemail  = (isset($data['custemail'])) ? $data['custemail'] : null;
		$this->custlastname  = (isset($data['custlastname'])) ? $data['custlastname'] : null;
		$this->custfirstname  = (isset($data['custfirstname'])) ? $data['custfirstname'] : null;
		$this->paymethod  = (isset($data['paymethod'])) ? $data['paymethod'] : null;
		$this->testorder  = (isset($data['testorder'])) ? $data['testorder'] : null;
		$this->postalcode  = (isset($data['postalcode'])) ? $data['postalcode'] : null;
		$this->city  = (isset($data['city'])) ? $data['city'] : null;
		$this->lineitem  = (isset($data['lineitem'])) ? $data['lineitem'] : null;
		$this->productid  = (isset($data['productid'])) ? $data['productid'] : null;
		$this->orderdate  = (isset($data['orderdate'])) ? $data['orderdate'] : null;
		$this->url  = (isset($data['url'])) ? $data['url'] : null;
		$this->unitprice  = (isset($data['unitprice'])) ? $data['unitprice'] : null;
		$this->storeid  = (isset($data['storeid'])) ? $data['storeid'] : null;
		$this->mfrPartNumber  = (isset($data['mfrPartNumber'])) ? $data['mfrPartNumber'] : null;
	}
}
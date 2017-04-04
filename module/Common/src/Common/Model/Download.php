<?php
namespace Common\Model;

class Download
{
	public $id;
	public $email;
	public $user_id;
	public $source_id;
	public $custfirstname;
	public $custlastname;
	public $isbn;
	public $url;
	public $unitprice;
	public $storeid;
	
	public function exchangeArray($data)
	{
		$this->id     = (isset($data['id'])) ? $data['id'] : null;
		$this->email     = (isset($data['email'])) ? $data['email'] : null;
		$this->user_id = (isset($data['user_id'])) ? $data['user_id'] : null;
		$this->source_id = (isset($data['source_id'])) ? $data['source_id'] : null;
		$this->custfirstname = (isset($data['custfirstname'])) ? $data['custfirstname'] : null;
		$this->custlastname = (isset($data['custlastname'])) ? $data['custlastname'] : null;
		$this->isbn = (isset($data['isbn'])) ? $data['isbn'] : null;
		$this->url = (isset($data['url'])) ? $data['url'] : null;
		$this->unitprice = (isset($data['unitprice'])) ? $data['unitprice'] : null;
		$this->storeid = (isset($data['storeid'])) ? $data['storeid'] : null;
	}
}
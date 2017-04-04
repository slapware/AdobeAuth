<?php
namespace Common\Model;

class Source
{
	public $id;
	public $user_id;
	public $orderidid;
	public $submissionDate;
	public $type;
	public $city;
	public $address1;
	public $site_name1;
	public $site_name2;
	public $site_phone;
	public $postalCode;
	public $state;

	public function exchangeArray($data)
	{
		$this->id     = (isset($data['id'])) ? $data['id'] : null;
		$this->user_id     = (isset($data['user_id'])) ? $data['user_id'] : null;
		$this->orderidid = (isset($data['orderidid'])) ? $data['orderidid'] : null;
		$this->submissionDate  = (isset($data['submissionDate'])) ? $data['submissionDate'] : null;
		$this->type  = (isset($data['type'])) ? $data['type'] : null;
		$this->city  = (isset($data['city'])) ? $data['city'] : null;
		$this->address1  = (isset($data['address1'])) ? $data['address1'] : null;
		$this->site_name1  = (isset($data['site_name1'])) ? $data['site_name1'] : null;
		$this->site_name2  = (isset($data['site_name2'])) ? $data['site_name2'] : null;
		$this->site_phone  = (isset($data['site_phone'])) ? $data['site_phone'] : null;
		$this->postalCode  = (isset($data['postalCode'])) ? $data['postalCode'] : null;
		$this->state  = (isset($data['state'])) ? $data['state'] : null;
	}
}
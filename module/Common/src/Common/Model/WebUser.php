<?php
namespace Common\Model;

class WebUser
{
	public $id;
	public $source_id;
	public $firstname;
	public $lastname;
	public $region;
	
	public function exchangeArray($data)
	{
		$this->id     = (isset($data['id'])) ? $data['id'] : null;
		$this->source_id     = (isset($data['source_id'])) ? $data['source_id'] : null;
		$this->firstname = (isset($data['firstname'])) ? $data['firstname'] : null;
		$this->lastname  = (isset($data['lastname'])) ? $data['lastname'] : null;
		$this->region  = (isset($data['region'])) ? $data['region'] : null;
	}
}
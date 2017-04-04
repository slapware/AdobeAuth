<?php
namespace Common\Model;

class User
{
	public $id;
	public $email;
	public $password;
	public $uuid;

	public function exchangeArray($data)
	{
		$this->id     = (isset($data['id'])) ? $data['id'] : null;
		$this->email     = (isset($data['email'])) ? $data['email'] : null;
		$this->password = (isset($data['password'])) ? $data['password'] : null;
		$this->uuid  = (isset($data['uuid'])) ? $data['uuid'] : null;
	}
}
<?php
namespace Common\Model;

class Error
{
	public $id;
	public $from;
	public $data;

	public function exchangeArray($data)
	{
		$this->id     = (isset($data['id'])) ? $data['id'] : null;
		$this->from     = (isset($data['from'])) ? $data['from'] : null;
		$this->data = (isset($data['data'])) ? $data['data'] : null;
	}
}
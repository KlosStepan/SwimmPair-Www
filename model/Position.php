<?php

class Position
{
	/** @var int */
	public $id;

	/** @var string */
	public $name;

	public function __construct($id, $name)
	{
		$this->id = $id;
		$this->name = $name;
	}

	//2/2 Full Serialization
	//id, name
	//{"id":"1","name":"Hlavní rozhodčí"};
	public function Serialize()
	{
		$_serialized = "{\"id\":\"".$this->id."\",\"name\":\"".$this->name."\"}";
		return $_serialized;
	}
}
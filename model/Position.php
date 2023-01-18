<?php
/**
 * Summary of Position
 */
class Position
{
	public $id;
	public $name;
	/**
	 * Summary of __construct
	 * @param mixed $id
	 * @param mixed $name
	 */
	public function __construct($id, $name)
	{
		$this->id = $id;
		$this->name = $name;
	}
	//2/2 Full Serialization
	//id, name
	//{"id":"1","name":"Hlavní rozhodčí"};
	/**
	 * Summary of Serialize
	 * @return string
	 */
	public function Serialize()
	{
		$_serialized = "{\"id\":\"" . $this->id . "\",\"name\":\"" . $this->name . "\"}";
		return $_serialized;
	}
}
<?php
/**
 * Position is object representing task for Cup that has to be performed by User. It has internal id based on which it is wired through the system internally.
 */
class Position
{
	public $id;
	public $name;
	/**
	 * Ctor of Position object for web application
	 * @param int $id
	 * @param string $name
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
	 * Serialize outputs 2/2 these members: id, name
	 * @return string
	 */
	public function Serialize()
	{
		$_serialized = "{\"id\":\"" . $this->id . "\",\"name\":\"" . $this->name . "\"}";
		return $_serialized;
	}
}
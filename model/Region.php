<?php
/**
 * Region is administrative object to which we affiliate each Club which each has some User objects. Region has no other purpose than just umbrella of regional clubs.  
 */
class Region
{
	public $id;
	public $name;
	public $abbreviation;
	/**
	 * Ctor of Region object for web application
	 * @param int $id
	 * @param string $name
	 * @param string $abbreviation
	 */
	public function __construct($id, $name, $abbreviation)
	{
		$this->id = $id;
		$this->name = $name;
		$this->abbreviation = $abbreviation;
	}
	//3/3: Full Serialization
	//id, name, abbreviation
	//{"id":1,"name":"Olomoucky kraj","abbreviation":OLK"}
	/**
	 * Serialize outputs 3/3 these members: id, name, abbreviation
	 * @return string
	 */
	public function Serialize()
	{
		$_serialized = "{\"id\":\"" . $this->id . "\",\"name\":\"" . $this->name . "\",\"abbreviation\":\"" . $this->abbreviation . "\"}";
		return $_serialized;
	}
}
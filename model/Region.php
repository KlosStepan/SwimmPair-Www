<?php
class Region
{
	/** @var id */
	public $id;

	/** @var string */
	public $name;

	/** @var string */
	public $abbreviation;

	public function __construct($id, $name, $abbreviation)
	{
		$this->id = $id;
		$this->name = $name;
		$this->abbreviation = $abbreviation;
	}

	//3/3: id, name, abbreviation
	//{"id":1,"name":"Olomoucky kraj","abbreviation":OLK"}
	public function Serialize()
	{
		$_serialized = "{\"id\":\"".$this->id."\",\"name\":\"".$this->name."\",\"abbreviation\":\"".$this->abbreviation."\"}";
		return $_serialized;
	}
}
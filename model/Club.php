<?php
/**
 * Club is an administrative unit of swimming club grouping bunch of users. One User is VedouciKlubu/1 from UserRights, the rest is Rozhodci/0. It can organize Cup via its users.
 */
class Club
{
	public $id;
	public $name;
	public $abbreviation;
	public $code;
	public $img;
	public $affiliation_region_id;
	/**
	 * Ctor of Club object for web application
	 * @param int $id
	 * @param string $name
	 * @param string $abbreviation
	 * @param int $code
	 * @param string $img
	 * @param int $affiliation_region_id
	 */
	public function __construct($id, $name, $abbreviation, $code, $img, $affiliation_region_id)
	{
		$this->id = $id;
		$this->name = $name;
		$this->abbreviation = $abbreviation;
		$this->code = $code;
		$this->img = $img;
		$this->affiliation_region_id = $affiliation_region_id;
	}
	//6/6 Full Serialization 
	//id, name, abbreviation, code, img, affiliation_region_id
	//{"id":"1","name":"Klub Prostejov","abbreviation":"KPV","code":"1","img":"prostejov.png","affiliation_region_id":"1"}
	/**
	 * SerializeFull outputs 6/6 these members: id, name, abbreviation, code, img, affiliation_region_id
	 * @return string
	 */
	public function SerializeFull()
	{
		$_serialized = "{\"id\":\"" . $this->id . "\",\"name\":\"" . $this->name . "\",\"abbreviation\":\"" . $this->abbreviation . "\",\"code\":\"" . $this->code . "\",\"img\":\"" . $this->img . "\",\"affiliation_region_id\":\"" . $this->affiliation_region_id . "\"}";
		return $_serialized;
	}
	//3/6 Slim Serialization
	//id, name, -NULL-, -NULL-, -NULL-, affiliation_region_id
	//{"id":"1","name":"Klub Prostejov","affiliation_region_id":"1"}
	/**
	 * SerializeSlim outputs 3/6 these members: id, name, -NULL-, -NULL-, -NULL-, affiliation_region_id
	 * @return string
	 */
	public function SerializeSlim()
	{
		$_serialized = "{\"id\":\"" . $this->id . "\",\"name\":\"" . $this->name . "\",\"affiliation_region_id\":\"" . $this->affiliation_region_id . "\"}";
		return $_serialized;
	}
}
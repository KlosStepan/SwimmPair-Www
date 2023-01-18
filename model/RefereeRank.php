<?php
/**
 * Summary of RefereeRank
 */
class RefereeRank
{
	public $id;
	public $rank_name;
	/**
	 * Summary of __construct
	 * @param mixed $id
	 * @param mixed $rank_name
	 */
	public function __construct($id, $rank_name)
	{
		$this->id = $id;
		$this->rank_name = $rank_name;
	}
	//2/2 Full Serialization
	//id, rank_name
	//{"id":"1","rank_name":"Hlavni rozhodci"}
	/**
	 * Summary of Serialize
	 * @return string
	 */
	public function Serialize()
	{
		$_serialized = "{\"id\":\"" . $this->id . "\",\"rank_name\":\"" . $this->rank_name . "\"}";
		return $_serialized;
	}
}
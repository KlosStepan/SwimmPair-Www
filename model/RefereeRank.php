<?php
/**
 * RefereeRank is object representing rank in referee hierarchy. It has internal id and caption of appropriate level in Czech Swimming Federation hierarchy.
 */
class RefereeRank
{
	public $id;
	public $rank_name;
	/**
	 * Ctor of RefereeRank object for web application
	 * @param int $id
	 * @param string $rank_name
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
	 * Serialize outputs 2/2 these members: id, rank_name
	 * @return string
	 */
	public function Serialize()
	{
		$_serialized = "{\"id\":\"" . $this->id . "\",\"rank_name\":\"" . $this->rank_name . "\"}";
		return $_serialized;
	}
}
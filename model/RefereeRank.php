<?php

class RefereeRank
{
	/** @var int*/
	public $id;

	/** @var string*/
	public $rank_name;

	public function __construct($id, $rank_name)
	{
		$this->id = $id;
		$this->rank_name = $rank_name;
	}

	public function Serialize()
	{
		$_serialized = "{\"id\":\"".$this->id."\",\"rank_name\":\"".$this->rank_name."\"}";
		return $_serialized;
	}
}
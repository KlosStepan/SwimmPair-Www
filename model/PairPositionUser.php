<?php

class PairPositionUser
{
	/** @var position_id */
	public $position_id;

	/** @var user_id */
	public $user_id;

	public function __construct($position_id, $user_id)
	{
		$this->position_id = $position_id;
		$this->user_id = $user_id;
	}
	
	//2/2 Full Serialization
	//position_id, user_id
	//{"position_id":"5","user_id":"21"}
	public function Serialize()
	{
		$_serialized = "{\"position_id\":\"".$this->position_id."\",\"user_id\":\"".$this->user_id."\"}";
		return $_serialized;
	}
}
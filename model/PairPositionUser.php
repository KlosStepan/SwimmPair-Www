<?php
/**
 * PairPositionUser is tuple of Position and User. It is used as pairing object for further use in web application. 
 */
class PairPositionUser
{
	public $position_id;
	public $user_id;
	/**
	 * Ctor of PairPositionUser object for web application
	 * @param int $position_id
	 * @param int $user_id
	 */
	public function __construct($position_id, $user_id)
	{
		$this->position_id = $position_id;
		$this->user_id = $user_id;
	}
	//
	//2/2 Full Serialization
	//position_id, user_id
	//{"position_id":"5","user_id":"21"}
	/**
	 * Serialize outputs 2/2 these members: position_id, user_id
	 * @return string
	 */
	public function Serialize()
	{
		$_serialized = "{\"position_id\":\"" . $this->position_id . "\",\"user_id\":\"" . $this->user_id . "\"}";
		return $_serialized;
	}
}
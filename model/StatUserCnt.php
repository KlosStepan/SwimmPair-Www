<?php

class StatUserCnt
{
	/**
	 * @var int
	 */
	public $user_id;

	/**
	 * @var int
	 */
	public $cnt;

	public function __construct($user_id, $cnt)
	{
		$this->user_id = $user_id;
		$this->cnt = $cnt;
	}
    //2/2 Full Serialization
	//user_id, cnt
	//{"user_id":"1","cnt":"2"};
	public function Serialize()
	{
		$_serialized = "{\"user_id\":\"" . $this->user_id . "\",\"cnt\":\"" . $this->cnt . "\"}";
		return $_serialized;
	}
}
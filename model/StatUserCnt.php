<?php
/**
 * Summary of StatUserCnt
 */
class StatUserCnt
{
	public $user_id;
	public $cnt;
	/**
	 * Summary of __construct
	 * @param mixed $user_id
	 * @param mixed $cnt
	 */
	public function __construct($user_id, $cnt)
	{
		$this->user_id = $user_id;
		$this->cnt = $cnt;
	}
	//2/2 Full Serialization
	//user_id, cnt
	//{"user_id":"1","cnt":"2"};
	/**
	 * Summary of Serialize
	 * @return string
	 */
	public function Serialize()
	{
		$_serialized = "{\"user_id\":\"" . $this->user_id . "\",\"cnt\":\"" . $this->cnt . "\"}";
		return $_serialized;
	}
}
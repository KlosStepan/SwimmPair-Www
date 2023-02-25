<?php
/**
 * StatUserCnt is object providing statistics count of User. 
 */
class StatUserCnt
{
	public $user_id;
	public $cnt;
	/**
	 * Ctor of StatUserCnt object for web application
	 * @param int $user_id
	 * @param int $cnt
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
	 * Serialize outputs 2/2 these members: user_id, cnt
	 * @return string
	 */
	public function Serialize()
	{
		$_serialized = "{\"user_id\":\"" . $this->user_id . "\",\"cnt\":\"" . $this->cnt . "\"}";
		return $_serialized;
	}
}
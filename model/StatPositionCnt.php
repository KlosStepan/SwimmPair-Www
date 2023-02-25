<?php
/**
 * StatPositionCnt is object providing count of statistics of Position.
 */
class StatPositionCnt
{
	public $position_id;
	public $cnt;
	/**
	 * Ctor of StatPositionCnt object for web application
	 * @param int $position_id
	 * @param int $cnt
	 */
	public function __construct($position_id, $cnt)
	{
		$this->position_id = $position_id;
		$this->cnt = $cnt;
	}
	//2/2 Full Serialization
	//position_id, cnt
	//{"position_id":"1","cnt":"2"};
	/**
	 * Serialize outputs 2/2 these members: position_id, cnt
	 * @return string
	 */
	public function Serialize()
	{
		$_serialized = "{\"position_id\":\"" . $this->position_id . "\",\"cnt\":\"" . $this->cnt . "\"}";
		return $_serialized;
	}
}
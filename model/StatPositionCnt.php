<?php
/**
 * Summary of StatPositionCnt
 */
class StatPositionCnt
{
	public $position_id;
	public $cnt;
	/**
	 * Summary of __construct
	 * @param mixed $position_id
	 * @param mixed $cnt
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
	 * Summary of Serialize
	 * @return string
	 */
	public function Serialize()
	{
		$_serialized = "{\"position_id\":\"" . $this->position_id . "\",\"cnt\":\"" . $this->cnt . "\"}";
		return $_serialized;
	}
}
<?php
class StatPositionCnt
{
	/**
	 * @var int
	 */
	public $position_id;

	/**
	 * @var int
	 */
	public $cnt;

	public function __construct($position_id, $cnt)
	{
		$this->position_id = $position_id;
		$this->cnt = $cnt;
	}

	//{"position_id":"1","cnt":"2"};
	public function Serialize()
	{
		$_serialized = "{\"position_id\":\"" . $this->position_id . "\",\"cnt\":\"" . $this->cnt . "\"}";
		return $_serialized;
	}
}
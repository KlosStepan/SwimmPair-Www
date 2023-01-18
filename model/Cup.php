<?php
/**
 * Summary of Cup
 */
class Cup
{
	public $id;
	public $time_start;
	public $time_end;
	public $name;
	public $description;
	public $organizer_club_id;
	/**
	 * Summary of __construct
	 * @param mixed $id
	 * @param mixed $time_start
	 * @param mixed $time_end
	 * @param mixed $name
	 * @param mixed $description
	 * @param mixed $organizer_club_id
	 */
	public function __construct($id, $time_start, $time_end, $name, $description, $organizer_club_id)
	{
		$this->id = $id;
		$this->time_start = $time_start;
		$this->time_end = $time_end;
		$this->name = $name;
		$this->description = $description;
		$this->organizer_club_id = $organizer_club_id;
	}
	//6/6 Full Serialization
	//id, time_start, time_end, name, description, owningclub
	//{"id":"7","time_start":"2018-07-16","time_end":"2018-07-18","name":"GJW Cup","description":"Letos v cervenci bude GJW cup","organizer_club_id":"5"}
	/**
	 * Summary of SerializeFull
	 * @return string
	 */
	public function SerializeFull()
	{
		$_serialized = "{\"id\":\"" . $this->id . "\",\"time_start\":\"" . $this->time_start . "\",\"time_end\":\"" . $this->time_end . "\",\"name\":\"" . $this->name . "\",\"description\":\"" . $this->description . "\",\"organizer_club_id\":\"" . $this->organizer_club_id . "\"}";
		return $_serialized;
	}
	//4/6 Slim Serialization
	//id, time_start, time_end, name, -NULL-, -NULL-
	//{"id":"7","time_start":"2018-07-16","time_end":"2018-07-18","name":"GJW Cup"}
	/**
	 * Summary of SerializeSlim
	 * @return string
	 */
	public function SerializeSlim()
	{
		$_serialized = "{\"id\":\"" . $this->id . "\",\"time_start\":\"" . $this->time_start . "\",\"time_end\":\"" . $this->time_end . "\",\"name\":\"" . $this->name . "\"}";
		return $_serialized;
	}
}
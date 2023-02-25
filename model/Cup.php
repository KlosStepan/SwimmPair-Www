<?php
/**
 * Cup is swimming cup organized by Club with User entities signed up to it. Each User usually has some Position to perform at Cup when it takes place.
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
	 * Ctor of Cup object for web application
	 * @param int $id
	 * @param string $time_start
	 * @param string $time_end
	 * @param int $name
	 * @param string $description
	 * @param int $organizer_club_id
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
	 * SerializeFull outputs 6/6 these members: id, time_start, time_end, name, description, owningclub
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
	 * SerializeSlim outputs 4/6 these members: id, time_start, time_end, name, -NULL-, -NULL-
	 * @return string
	 */
	public function SerializeSlim()
	{
		$_serialized = "{\"id\":\"" . $this->id . "\",\"time_start\":\"" . $this->time_start . "\",\"time_end\":\"" . $this->time_end . "\",\"name\":\"" . $this->name . "\"}";
		return $_serialized;
	}
}
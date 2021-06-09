<?php

class Cup
{
	/** @var int */
	public $id;

	/** @var date */
	public $time_start;

	/** @var date */
	public $time_end;

	/** @var string */
	public $name;

	/** @var string */
	public $description;

	/** @var int */
	public $organizer_club_id;

	public function __construct($id, $time_start, $time_end, $name, $description, $organizer_club_id)
	{
		$this->id = $id;
		$this->time_start = $time_start;
		$this->time_end = $time_end;
		$this->name = $name;
		$this->description = $description;
		$this->organizer_club_id = $organizer_club_id;
	}

	//6/6: id, time_start, time_end, name, description, owningclub
	//{"id":"7","time_start":"2018-07-16","time_end":"2018-07-18","name":"CMG Cup","description":"Letos v cervenci bude CMG cup","organizer_club_id":"5"}
	public function SerializeFull()
	{
		$_serialized = "{\"id\":\"".$this->id."\",\"time_start\":\"".$this->time_start."\",\"time_end\":\"".$this->time_end."\",\"name\":\"".$this->name."\",\"description\":\"".$this->description."\",\"organizer_club_id\":\"".$this->organizer_club_id."\"}";
		return $_serialized;
	}

	//4/6: id, time_start, time_end, name, -NULL-, -NULL-
	//{"id":"7","time_start":"2018-07-16","time_end":"2018-07-18","name":"CMG Cup"}
	public function SerializeSlim()
	{
		$_serialized = "{\"id\":\"".$this->id."\",\"time_start\":\"".$this->time_start."\",\"time_end\":\"".$this->time_end."\",\"name\":\"".$this->name."\"}";
		return $_serialized;
	}
}
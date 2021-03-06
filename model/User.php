<?php
class User
{
	/** @var int */
	public $id;

	/** @var string */
	public $first_name;

	/** @var string */
	public $last_name;

	/** @var string */
	public $email;

	/** @var tinyint (1yes/0no) */
	public $approved_flag;

	/** @var int (2~SuperUser, 1~VedouciKlubu, 0~Rozhodci) */
	public $rights;

	/** @var int */
	public $referee_rank_id;

	/** @var int */
	public $affiliation_club_id;

	public function __construct($id, $first_name, $last_name, $email, $approved_flag, $rights, $referee_rank_id, $affiliation_club_id)
	{
		$this->id = $id;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->email = $email;
		$this->approved_flag = $approved_flag;
		$this->rights = $rights;
		$this->referee_rank_id = $referee_rank_id;
		$this->affiliation_club_id = $affiliation_club_id;
	}

	//Full serialization
	//8/8: id, first_name, last_name, email, approved_flag, rights, referee_rank_id, affiliation_club_id
	//{"id":"12","first_name":"Lukas","last_name":"Kousal","email":"lukas.kousal@seznam.cz","approved_flag":"1","rights":"2","referee_rank_id,"2","affiliation_club_id":"7"}
	public function SerializeFull()
	{
		$_serialized = "{\"id\":\"".$this->id."\",\"first_name\":\"".$this->first_name."\",\"last_name\":\"".$this->last_name."\",\"email\":\"".$this->email."\",\"approved_flag\":\"".$this->approved_flag."\",\"rights\":\"".$this->rights."\",\"referee_rank_id\":\"".$this->referee_rank_id."\",\"affiliation_club_id\":\"".$this->affiliation_club_id."\"}";
		return $_serialized;
	}

	//Slim serialization
	//5/8: id, first_name, last_name, -N/A-, -N/A-, -N/A-, referee_rank_id, affiliation_club_id
	//{"id":"12","first_name":"Lukas","last_name":"Kousal","affiliation_club_id":"7"}
	public function SerializeSlim()
	{
		$_serialized = "{\"id\":\"".$this->id."\",\"first_name\":\"".$this->first_name."\",\"last_name\":\"".$this->last_name."\",\"referee_rank_id\":\"".$this->referee_rank_id."\",\"affiliation_club_id\":\"".$this->affiliation_club_id."\"}";
		return $_serialized;
	}

	//Tag serialization
	//2/8: -NULL-, first_name, last_name, -NULL-, -NULL-, -NULL-, -NULL-, -NULL-
	//{"first_name":"Lukas","last_name":"Kousal"}
	public function SerializeTag()
	{
		$_serialized = "{\"id\":\"null\",\"first_name\":\"".$this->first_name."\",\"last_name\":\"".$this->last_name."\",\"email\":\"null\",\"approved_flag\":\"null\",\"rights\":\"null\",\"referee_rank_id\":\"null\",\"affiliation_club_id\":\"null\"}";
		return $_serialized;
	}
}
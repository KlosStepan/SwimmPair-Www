<?php
/**
 * Summary of User
 */
class User
{
	public $id;
	public $first_name;
	public $last_name;
	public $email;
	public $approved_flag; //(1yes/0no)
	public $rights; //(2~SuperUser, 1~VedouciKlubu, 0~Rozhodci)
	public $referee_rank_id;
	public $affiliation_club_id;
	/**
	 * Summary of __construct
	 * @param mixed $id
	 * @param mixed $first_name
	 * @param mixed $last_name
	 * @param mixed $email
	 * @param mixed $approved_flag
	 * @param mixed $rights
	 * @param mixed $referee_rank_id
	 * @param mixed $affiliation_club_id
	 */
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
	//8/8 Full Serialization
	//id, first_name, last_name, email, approved_flag, rights, referee_rank_id, affiliation_club_id
	//{"id":"12","first_name":"Lukas","last_name":"Kousal","email":"lukas.kousal@seznam.cz","approved_flag":"1","rights":"2","referee_rank_id,"2","affiliation_club_id":"7"}
	/**
	 * Summary of SerializeFull
	 * @return string
	 */
	public function SerializeFull()
	{
		$_serialized = "{\"id\":\"" . $this->id . "\",\"first_name\":\"" . $this->first_name . "\",\"last_name\":\"" . $this->last_name . "\",\"email\":\"" . $this->email . "\",\"approved_flag\":\"" . $this->approved_flag . "\",\"rights\":\"" . $this->rights . "\",\"referee_rank_id\":\"" . $this->referee_rank_id . "\",\"affiliation_club_id\":\"" . $this->affiliation_club_id . "\"}";
		return $_serialized;
	}
	//5/8 Slim Serialization
	//id, first_name, last_name, -N/A-, -N/A-, -N/A-, referee_rank_id, affiliation_club_id
	//{"id":"12","first_name":"Lukas","last_name":"Kousal","affiliation_club_id":"7"}
	/**
	 * Summary of SerializeSlim
	 * @return string
	 */
	public function SerializeSlim()
	{
		$_serialized = "{\"id\":\"" . $this->id . "\",\"first_name\":\"" . $this->first_name . "\",\"last_name\":\"" . $this->last_name . "\",\"referee_rank_id\":\"" . $this->referee_rank_id . "\",\"affiliation_club_id\":\"" . $this->affiliation_club_id . "\"}";
		return $_serialized;
	}
	//2/8 Tag Serialization
	//-NULL-, first_name, last_name, -NULL-, -NULL-, -NULL-, -NULL-, -NULL-
	//{"first_name":"Lukas","last_name":"Kousal"}
	/**
	 * Summary of SerializeTag
	 * @return string
	 */
	public function SerializeTag()
	{
		$_serialized = "{\"id\":\"null\",\"first_name\":\"" . $this->first_name . "\",\"last_name\":\"" . $this->last_name . "\",\"email\":\"null\",\"approved_flag\":\"null\",\"rights\":\"null\",\"referee_rank_id\":\"null\",\"affiliation_club_id\":\"null\"}";
		return $_serialized;
	}
}
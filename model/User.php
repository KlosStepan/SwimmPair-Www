<?php
/**
 * User is referee in the system. User is affiliated to Club has one of UserRights and one of tier from RefereeRank. His statistics are presented via StatUserCnt in public sites via PositionsManager.
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
	 * Ctor of User object for web application
	 * @param int $id
	 * @param string $first_name
	 * @param string $last_name
	 * @param string $email
	 * @param bool $approved_flag
	 * @param int $rights
	 * @param int $referee_rank_id
	 * @param int $affiliation_club_id
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
	 * Serialize outputs 8/8 these members: id, first_name, last_name, email, approved_flag, rights, referee_rank_id, affiliation_club_id
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
	 * Serialize outputs 5/8 these members: id, first_name, last_name, -N/A-, -N/A-, -N/A-, referee_rank_id, affiliation_club_id
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
	 * Serialize outputs 2/8 these members: -NULL-, first_name, last_name, -NULL-, -NULL-, -NULL-, -NULL-, -NULL-
	 * @return string
	 */
	public function SerializeTag()
	{
		$_serialized = "{\"id\":\"null\",\"first_name\":\"" . $this->first_name . "\",\"last_name\":\"" . $this->last_name . "\",\"email\":\"null\",\"approved_flag\":\"null\",\"rights\":\"null\",\"referee_rank_id\":\"null\",\"affiliation_club_id\":\"null\"}";
		return $_serialized;
	}
}
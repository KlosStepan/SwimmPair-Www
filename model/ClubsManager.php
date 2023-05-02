<?php
/**
 * ClubsManager has API functions to handle Club object/s and delivers it through web application.
 */
class ClubsManager
{
	private $mysqli;
	/**
	 * Initialize ClubsManager with live database connection.
	 * @param mysqli $mysqli
	 */
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}
	/**
	 * Retrieve list of all clubs.
	 * @return array<Club>
	 */
	public function FindAllClubs()
	{
		//$statement = $this->mysqli->prepare('SELECT `id`, `name`, `abbreviation`, `code`, `img`, `affiliation_region_id` FROM `sp_clubs` ');
		$statement = $this->mysqli->prepare('CALL `FindAllClubs`()');

		return $this->_CreateClubsFromStatement($statement);
	}
	/**
	 * Get Region id based on Club id.
	 * @param int $clubID
	 * @return int
	 */
	public function GetClubAffiliationToRegion($clubID)
	{
		//$statement = $this->mysqli->prepare('SELECT `affiliation_region_id` FROM `sp_clubs` WHERE `sp_clubs`.`id`=?');
		$statement = $this->mysqli->prepare('CALL `GetClubAffiliationToRegion`(?)');
		$statement->bind_param('i', $clubID);

		return $this->_GetSingleResultFromStatement($statement);
	}
	//Club handling
	/**
	 * Get Club based on id.
	 * @param int $clubID
	 * @return Club|null
	 */
	public function GetClubByID($clubID)
	{
		$statement = $this->mysqli->prepare('CALL `GetClubByID`(?)');
		$statement->bind_param('i', $clubID);
		return $this->_CreateClubFromStatement($statement);
	}
	/**
	 * Create new Club in the web applicaton.
	 * @param string $name
	 * @param string $abbreviation
	 * @param int $club_id
	 * @param string $img
	 * @param int $kraj
	 * @return bool
	 */
	public function InsertNewClub($name, $abbreviation, $club_id, $img, $kraj)
	{
		$statement = $this->mysqli->prepare('CALL `InsertNewClub`(?, ?, ?, ?, ?)');
		$statement->bind_param('ssisi', $name, $abbreviation, $club_id, $img, $kraj);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Update Club information.
	 * @param int $id
	 * @param string $name
	 * @param string $abbreviation
	 * @param string $code
	 * @param string $img
	 * @param int $affiliation_region_id
	 * @return bool
	 */
	public function UpdateClub($id, $name, $abbreviation, $code, $img, $affiliation_region_id)
	{
		$statement = $this->mysqli->prepare('CALL `UpdateClub`(?,?,?,?,?,?)');
		$statement->bind_param('issisi', $id, $name, $abbreviation, $code, $img, $affiliation_region_id);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	//PRIVATE FUNCTIONS - ORM-ing DB client lib results
	/**
	 * Create list of clubs from returned database statement.
	 * @param mysqli_stmt $statement
	 * @return array<Club>
	 */
	private function _CreateClubsFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
		$clubs = [];
		/**
		 * Iterate through each row and create a club object
		 */
		foreach ($rows as $row) {
			$clubs[] = $this->_CreateClubFromRow($row);
		}
		return $clubs;
	}
	/**
	 * Create Club object from database statement row/associate array.
	 * @param array $row
	 * @return Club
	 */
	private function _CreateClubFromRow(array $row)
	{
		return new Club($row['id'], $row['name'], $row['abbreviation'], $row['code'], $row['img'], $row['affiliation_region_id']);
	}
	/**
	 * Create single Club from database statement.
	 * @param mysqli_stmt $statement
	 * @return Club|null
	 */
	private function _CreateClubFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$result = $statement->get_result();
		//no reuslt
		if ($result === false) {
			return NULL;
		}
		$row = $result->fetch_assoc();
		if ($row !== NULL) {
			return $this->_CreateClubFromRow($row);
		} else {
			return NULL;
		}
	}
	/**
	 * Get one thing from database from only position in one row.
	 * @param mysqli_stmt $statement
	 * @return mixed
	 */
	private function _GetSingleResultFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_NUM);
		$row = $rows[0];
		return $row[0];
	}
}
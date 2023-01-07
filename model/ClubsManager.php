<?php

class ClubsManager
{
	/**
	 * @var mysqli
	 */
	private $mysqli;

	/**
	 * ClubsManager constructor.
	 * @param mysqli $mysqli
	 */
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}

	/**
	 * @return Club[]
	 */
	public function FindAllClubs()
	{
		//$statement = $this->mysqli->prepare('SELECT `id`, `name`, `abbreviation`, `code`, `img`, `affiliation_region_id` FROM `sp_clubs` ');
		$statement = $this->mysqli->prepare('CALL `FindAllClubs`()');

		return $this->_CreateClubsFromStatement($statement);
	}

	/**
	 * @param mysqli_stmt $statement
	 * @return array
	 */
	private function _CreateClubsFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

		$clubs = [];
		foreach($rows as $row)
		{
			$clubs[] = $this->_CreateClubFromRow($row);
		}

		return $clubs;
	}

	/**
	 * @param array $row
	 * @return Club
	 */
	private function _CreateClubFromRow(array $row)
	{
		return new Club($row['id'], $row['name'], $row['abbreviation'], $row['code'], $row['img'], $row['affiliation_region_id']);
	}



	/**
	 * @param $clubID
	 * @return Club|null
	 */
	public function GetClubByID($clubID)
	{
		//$statement = $this->mysqli->prepare('SELECT `id`, `name`, `abbreviation`, `code`, `img`, `affiliation_region_id` FROM `sp_clubs` WHERE id=? LIMIT 1');
		$statement = $this->mysqli->prepare('CALL `GetClubByID`(?)');
		$statement->bind_param('i',$clubID);

		return $this->_CreateClubFromStatement($statement);
	}

	/**
	 * @param mysqli_stmt $statement
	 * @return Club|null
	 */
	private function _CreateClubFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$row = $statement->get_result()->fetch_assoc();
		if ($row!==NULL)
		{
			return $this->_CreateClubFromRow($row);
		}
		else
		{
			return NULL;
		}
	}

	/**
	 * @param $name
	 * @param $abbreviation
	 * @param $club_id
	 * @param $img
	 * @param $kraj
	 * @return bool
	 */
	public function InsertNewClub($name, $abbreviation, $club_id, $img, $kraj)
	{
		//$statement = $this->mysqli->prepare('INSERT INTO `sp_clubs` (`id`, `name`, `abbreviation`, `code`, `img`, `affiliation_region_id`) VALUES (NULL, ?, ?, ?, ?, ?)');
		$statement = $this->mysqli->prepare('CALL `InsertNewClub`(?, ?, ?, ?, ?)');
		$statement->bind_param('ssisi', $name, $abbreviation, $club_id, $img, $kraj);
		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * @param $clubID
	 * @return mixed
	 */
	public function GetClubAffiliationToRegion($clubID)
	{
		//$statement = $this->mysqli->prepare('SELECT `affiliation_region_id` FROM `sp_clubs` WHERE `sp_clubs`.`id`=?');
		$statement = $this->mysqli->prepare('CALL `GetClubAffiliationToRegion`(?)');
		$statement->bind_param('i',$clubID);

		return $this->_GetSingleResultFromStatement($statement);
	}

	/** CP _GetSingleResultFromStatement(mysqli_stmt $statement){} HERE */
	/**
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

	//OK
	public function UpdateClub($id, $name, $abbreviation, $code, $img, $affiliation_region_id)
	{
		//$statement = $this->mysqli->prepare('UPDATE `sp_clubs` SET `name`=?, `abbreviation`=?, `code`=?, `img`=?, `affiliation_region_id`=? WHERE `id`=?');
		$statement = $this->mysqli->prepare('CALL `UpdateClub`(?,?,?,?,?,?)');
		//$statement->bind_param('ssisii', $name, $abbreviation, $code, $img, $affiliation_region_id, $id);
		$statement->bind_param('issisi', $id, $name, $abbreviation, $code, $img, $affiliation_region_id);
		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
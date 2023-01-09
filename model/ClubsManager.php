<?php

class ClubsManager
{
	private $mysqli;
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}
	//
	public function FindAllClubs()
	{
		//$statement = $this->mysqli->prepare('SELECT `id`, `name`, `abbreviation`, `code`, `img`, `affiliation_region_id` FROM `sp_clubs` ');
		$statement = $this->mysqli->prepare('CALL `FindAllClubs`()');
		
		return $this->_CreateClubsFromStatement($statement);
	}
	public function GetClubAffiliationToRegion($clubID)
	{
		//$statement = $this->mysqli->prepare('SELECT `affiliation_region_id` FROM `sp_clubs` WHERE `sp_clubs`.`id`=?');
		$statement = $this->mysqli->prepare('CALL `GetClubAffiliationToRegion`(?)');
		$statement->bind_param('i',$clubID);

		return $this->_GetSingleResultFromStatement($statement);
	}
	//Club handling
	public function GetClubByID($clubID)
	{
		$statement = $this->mysqli->prepare('CALL `GetClubByID`(?)');
		$statement->bind_param('i',$clubID);
		return $this->_CreateClubFromStatement($statement);
	}
	public function InsertNewClub($name, $abbreviation, $club_id, $img, $kraj)
	{
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
	public function UpdateClub($id, $name, $abbreviation, $code, $img, $affiliation_region_id)
	{
		$statement = $this->mysqli->prepare('CALL `UpdateClub`(?,?,?,?,?,?)');
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
	//PRIVATE FUNCTIONS - ORM-ing DB client lib results
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
	private function _CreateClubFromRow(array $row)
	{
		return new Club($row['id'], $row['name'], $row['abbreviation'], $row['code'], $row['img'], $row['affiliation_region_id']);
	}
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
	private function _GetSingleResultFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_NUM);
		$row = $rows[0];
		return $row[0];
	}
}
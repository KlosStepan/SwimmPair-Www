<?php

class RegionsManager
{
	private $mysqli;
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}
	//
	public function FindAllRegions()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllRegions`()');
		return $this->_CreateRegionsFromStatement($statement);
	}
	public function GetRegionNameOfClub($regionID)
	{
		$statement = $this->mysqli->prepare('CALL `GetRegionNameOfClub`(?)');
		$statement->bind_param('i',$regionID);
		return $this->_GetSingleResultFromStatement($statement);
	}
	//Region handling
	public function GetRegionByID($regionID)
	{
		$statement = $this->mysqli->prepare('CALL `GetRegionByID`(?)');
		$statement->bind_param('i',$regionID);
		return $this->_CreateRegionFromStatement($statement);
	}
	public function InsertNewRegion($name, $abbrev)
	{
		$statement = $this->mysqli->prepare('CALL `InsertNewRegion`(?, ?)');
		$statement->bind_param('ss', $name, $abbrev);
		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function UpdateRegion($id, $name, $abbrev)
	{
		$statement = $this->mysqli->prepare('CALL `UpdateRegion`(?,?,?)');
		$statement->bind_param('iss', $id, $name, $abbrev);
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
	private function _CreateRegionsFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

		$regions = [];
		foreach($rows as $row) {
			$regions[] = $this->_CreateRegionFromRow($row);
		}

		return $regions;
	}
	private function _CreateRegionFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$row = $statement->get_result()->fetch_assoc();
		if ($row!==NULL)
		{
			return $this->_CreateRegionFromRow($row);
		}
		else
		{
			return NULL;
		}
	}
	private function _CreateRegionFromRow(array $row)
	{
		return new Region($row['id'], $row['name'], $row['abbreviation']);
	}
	private function _GetSingleResultFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_NUM);
		$row = $rows[0];
		return $row[0];
	}
}